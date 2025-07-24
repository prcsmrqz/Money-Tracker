<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        // paginated the raw transactions
        $paginated = auth()->user()
            ->transactions()
            ->where('category_id', $category->id)
            ->with('category')
            ->latest()
            ->paginate(5); 

        // group paginated transactions by date
        $groupedTransactions = $paginated->getCollection()
            ->groupBy(function ($transaction) {
                return $transaction->created_at->format('Y-m-d');
            });
        
        // sum transactions by type per date
        $sumByTypePerDate = [];
        foreach ($groupedTransactions as $date => $transactions) {
            $sumByTypePerDate[$date] = [
                'income' => $transactions->where('type', 'income')->sum('amount'),
                'expenses' => $transactions->where('type', 'expenses')->sum('amount'),
                'savings' => $transactions->where('type', 'savings')->sum('amount'),
            ];
        }

        // Replace the original collection with the grouped one
        $paginated->setCollection($groupedTransactions);

        return view('category.show', [
            'transactions' => $paginated,
            'sumByTypePerDate' => $sumByTypePerDate,
            'category' => $category,
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
