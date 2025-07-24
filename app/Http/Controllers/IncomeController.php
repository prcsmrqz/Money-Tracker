<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {

        $categories = auth()->user()->categories()->where('type', 'income')->orderBy('name', 'ASC')->get();

        foreach ($categories as $category) {
            $category->totalIncome = auth()->user()->transactions()
                ->where('type', 'income')
                ->where('category_id', $category->id)
                ->sum('amount');
        }
        return view('income.index', compact('categories'));
    }


}
