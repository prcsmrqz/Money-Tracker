<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CategoryController extends Controller
{
    
    public function show(Category $category)
    {
       // Get base query if income then get the source income
        if ($category->type === 'income') {
            $baseQuery = auth()->user()->transactions()
                ->where(function ($query) use ($category) {
                    $query->where('category_id', $category->id)
                        ->orWhere('source_income', $category->id);
                });
        } else {
            $baseQuery = auth()->user()->transactions()
                ->where('category_id', $category->id);
        }

    // Get oldest date's year
    $oldestDate = (clone $baseQuery)->orderBy('date', 'asc')->value('date');
    $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : now()->year;

    // Build the main query (filtered with Spatie QueryBuilder)
    $query = QueryBuilder::for($baseQuery)
        ->with(['category', 'savingsAccount', 'sourceIncomeCategory', 'sourceSavingsAccount'])
        ->allowedFilters([
            AllowedFilter::callback('search', function ($query, $value) {
                $query->where(function ($q) use ($value) {
                    $q->where('notes', 'like', "%{$value}%")
                        ->orWhere('type', 'like', "%{$value}%")
                        ->orWhere('amount', 'like', "%{$value}%");
                });
            }),
        ])
        ->orderBy('date', 'desc');


        if (request('date_filter')) {
            $dateFilter = request('date_filter');
            if($dateFilter == 'today'){
                $query->whereDate('date', Carbon::today());
            } else if ($dateFilter == 'last_7_days'){
                $query->whereBetween('date', [
                    Carbon::now()->subDays(6)->startOfDay(),
                    Carbon::now()->endOfDay(),
                ]);
            } else if ($dateFilter == 'last_30_days') {
                $query->whereBetween('date', [
                    Carbon::now()->subDays(30)->startOfDay(),
                    Carbon::now()->endOfDay(),
                ]);
            } 
            
        }

        if (request('month_filter') && request('year_filter')) {
            $monthFilter = request('month_filter');
            $yearFilter = request('year_filter');
                $query->whereMonth('date', $monthFilter )->whereYear('date', $yearFilter);
        }

        if (request('start') && request('end')) {
            $query->whereBetween('date', [
                Carbon::parse(request('start'))->startOfDay(),
                Carbon::parse(request('end'))->endOfDay()
            ]);
        }

        $paginated = $query->paginate(5)->withQueryString();

        $groupedTransactions = $paginated->getCollection()
            ->groupBy(fn($transaction) => $transaction->date->format('Y-m-d'));

        $sumByTypePerDate = [];
        
        foreach ($groupedTransactions as $date => $transactions) {
            $sumByTypePerDate[$date] = [
                'income' => $transactions->where('type', 'income')->sum('amount'),
                'expenses' => $transactions->where('type', 'expenses')->sum('amount'),
                'savings' => $transactions->where('type', 'savings')->sum('amount'),
            ];
        }
        
        $paginated->setCollection($groupedTransactions);


        $savingsAccounts = auth()->user()->savingsAccount()->orderBy('name', 'ASC')->get();
        $categoriesType = auth()->user()->categories()->get();

        return view('category.show', [
            'transactions' => $paginated,
            'sumByTypePerDate' => $sumByTypePerDate,
            'category' => $category,
            'categories' => $categoriesType,
            'oldestYear' => $oldestYear,
            'savingsAccounts' => $savingsAccounts,
        ]);
    }


     public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['icon'] = $request->file('icon') ? $request->file('icon')->store('icons', 'public') : null;

        auth()->user()->categories()->create($data);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = auth()->user()->categories()->findOrFail($id);

        try {
            $data = $request->validated();
            $field['name'] = $data['name_' . $id] ?? null;
            $field['color'] = $data['color_' . $id] ?? null;
            $field['icon'] = $request->file('iconEdit_' . $id)
                ? $request->file('iconEdit_' . $id)->store('icons', 'public')
                : $category->icon;

            $category->update($field);

            return redirect()->back()->with('success', 'Category updated successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator, 'update');
        }
    }

    public function destroy($id)
    {
        $category = auth()->user()->categories()->findOrFail($id);
        if ($category){
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            $category->delete();
            return redirect()->back()->with('success', 'Category deleted successfully.');
        }

        return redirect()->back()->with('error', 'Category not found.');
        
    }
}
