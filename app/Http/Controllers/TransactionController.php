<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories()->where('type', 'income')->orderBy('name', 'ASC')->get();
        return view("transaction.index", compact("categories"));
    }

    public function store(TransactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        auth()->user()->transactions()->create($data);
        return redirect()->back()->with('success', 'Income transaction created successfully.');
    }
}
