<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    public function update(Request $request, $id)
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);

        try {
            $data = Validator::make($request->all(), [
                'amount' => ['required', 'numeric'],
                'notes' => ['nullable', 'string', 'max:255'],
                'date' => ['required', 'date'],
            ])->validate();

            $transaction->update($data);

            return redirect()
                ->route('category.show', $transaction->category_id)
                ->with('success', 'Transaction updated successfully.');

        } catch (ValidationException $e) {
            return redirect()
                ->route('category.show', $transaction->category_id)
                ->withErrors($e->validator, 'update')
                ->withInput()
                ->with('error_transaction_id', $id);
        }
    }

    public function destroy($id) {
        $transaction = auth()->user()->transactions()->findOrFail($id);

        $transaction->delete();
        return redirect()->back()->with('success','Transaction deleted successfully.');
    }
}
