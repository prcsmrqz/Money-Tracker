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
        $savingsAccounts = auth()->user()->savingsAccounts()->orderBy('name','ASC')->get();
        $activeTab = session('activeTab', 'income'); 
        
        return view("transaction.index", compact('categories', 'savingsAccounts', 'activeTab'));
    }

    public function store(TransactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $type = $data['type'];
        auth()->user()->transactions()->create($data);
        return redirect()->back()->with('success', "{$type} transaction created successfully.")->with('activeTab', $type);
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
            
            if ($transaction->category_id){
                return redirect()->route('category.show', $transaction->category_id)
                ->with('success', 'Transaction updated successfully.');
            } else if ($transaction->savings_account_id){
                return redirect()->route('savings.show', $transaction->savings_account_id)
                ->with('success', 'Transaction updated successfully.');
            }

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
