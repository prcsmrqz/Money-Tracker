<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\FilterService;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    
    public function show(Category $category, FilterService $filterService)
    {
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

        // Oldest year
        $oldestDate = (clone $baseQuery)->orderBy('date', 'asc')->value('date');
        $oldestYear = $oldestDate ? \Carbon\Carbon::parse($oldestDate)->year : now()->year;

        // Pass the query and with() list
        [$paginated, $sumByTypePerDate] = $filterService->filter(
            $baseQuery,
            ['category', 'savingsAccount', 'sourceIncomeCategory', 'sourceSavingsAccount'],
            'group'
        );

        $savingsAccounts = auth()->user()->savingsAccount()->orderBy('name', 'ASC')->get();
        $allCategories = auth()->user()->categories()->get();

        return view('category.show', [
            'transactions' => $paginated,
            'sumByTypePerDate' => $sumByTypePerDate,
            'category' => $category,
            'allCategories' => $allCategories,
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
