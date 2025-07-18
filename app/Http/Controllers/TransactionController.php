<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function income()
    {
        
        $categories = auth()->user()->categories()->where('type', 'income')->orderBy('name', 'ASC')->get();
        return view("transaction.income", compact("categories"));
    }

    public function store(Request $request)
    {

    }
}
