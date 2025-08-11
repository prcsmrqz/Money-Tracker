<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories()->where('type', 'income')->orderBy('name', 'ASC')->get();
        $expensesCategories = auth()->user()->categories()->where('type', 'expenses')->orderBy('name', 'ASC')->get();
        $savingsAccounts = auth()->user()->savingsAccounts()->orderBy('name','ASC')->get();
        $activeTab = session('activeTab', 'income'); 
        
        return view("transaction.index", compact('categories', 'savingsAccounts', 'activeTab', 'expensesCategories'));
    }

    public function store(TransactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $type = $data['type'];
        auth()->user()->transactions()->create($data);

        return redirect()->back()->with('success', ucfirst($type) . ' transaction created successfully.')->with('activeTab', $type);
    }

    public function update(Request $request, $id)
{
    $transaction = auth()->user()->transactions()->findOrFail($id);

    try {
        $validator = Validator::make($request->all(), [
    'amount' => ['required', 'numeric'],
    'notes' => ['nullable', 'string', 'max:255'],
    'date' => ['required', 'date'],
    'source_savings' => [
        'integer',
        Rule::requiredIf(function () use ($request) {
            return $request->input('type') === 'expenses' && $request->input('source_type') === 'savings';
        }),
        Rule::exists('savings_accounts', 'id'),
    ],
    'source_income' => [
        'integer',
        Rule::requiredIf(function () use ($request) {
            return $request->input('type') === 'expenses' && $request->input('source_type') === 'income';
        }),
        Rule::exists('categories', 'id')->where('type', 'income'),
    ],
]);


        $validator->after(function ($validator) use ($request) {
            $type = $request->input('type');
            $amount = $request->input('amount');

            if ($type === 'expenses') {
                $user = auth()->user();

                if ($request->input('source_type') === 'savings') {
                    $sourceId = $request->input('source_savings');

                    $totalSavings = $user->transactions()
                        ->where('savings_account_id', $sourceId)
                        ->where('type', 'savings')
                        ->sum('amount');

                    $totalExpenses = $user->transactions()
                        ->where('source_savings', $sourceId)
                        ->where('type', 'expenses')
                        ->sum('amount');

                    $remaining = $totalSavings - $totalExpenses;

                    if ($amount > $remaining) {
                        $validator->errors()->add('amount', 'The amount exceeds the remaining savings (₱' . number_format($remaining, 2) . ').');
                    }

                } elseif ($request->input('source_type') === 'income') {
                    $sourceId = $request->input('source_income');

                    $totalIncome = $user->transactions()
                        ->where('category_id', $sourceId)
                        ->where('type', 'income')
                        ->sum('amount');

                    $totalExpenses = $user->transactions()
                        ->where('source_income', $sourceId)
                        ->where('type', 'expenses')
                        ->sum('amount');

                    $remaining = $totalIncome - $totalExpenses;

                    if ($amount > $remaining) {
                        $validator->errors()->add('amount', 'The amount exceeds the remaining income (₱' . number_format($remaining, 2) . ').');
                    }
                }
            }
        });

        $data = $validator->validate();

        $transaction->update($data);

        if ($transaction->category_id) {
            return redirect()->route('category.show', $transaction->category_id)
                ->with('success', 'Transaction updated successfully.');
        } elseif ($transaction->savings_account_id) {
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
