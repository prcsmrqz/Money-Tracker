<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {

        $categories = auth()->user()->categories()->where('type', 'income')->get();
        return view('income.index', compact('categories'));
    }


}
