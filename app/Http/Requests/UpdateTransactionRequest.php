<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Or add your authorization logic
    }

    public function rules(): array
    {
        $request = $this; // For closure usage

        return [
            'amount' => [
                'required',
                'numeric',
                'min: 1',
                // Expenses from savings
                Rule::when(
                    $request->input('type') === 'expenses' && $request->input('source_type') === 'savings',
                    function () use ($request) {
                        return function ($attribute, $value, $fail) use ($request) {
                            $sourceType = $request->input('source_savings');
                            $id = $request->route('transaction') ?? $request->route('id') ?? $request->input('id');
                            $user = auth()->user();

                            $totalSavings = $user->transactions()
                                ->where('savings_account_id', $sourceType)
                                ->where('type', 'savings')
                                ->sum('amount');

                            $totalExpenses = $user->transactions()
                                ->where('source_savings', $sourceType)
                                ->where('type', 'expenses')
                                ->where('id', '!=', $id)
                                ->sum('amount');
                                

                            $remainingSavings = $totalSavings - $totalExpenses;

                            if ($value > $remainingSavings) {
                                $fail(
                                    'Amount exceeds remaining savings for the selected category: ₱' 
                                    . number_format(max(0, $remainingSavings), 2) 
                                    . ' (includes this transaction).'
                                );
                            }
                        };
                    }
                ),

                // Expenses from income
                Rule::when(
                    $request->input('type') === 'expenses' && $request->input('source_type') === 'income',
                    function () use ($request) {
                        return function ($attribute, $value, $fail) use ($request) {
                            $sourceType = $request->input('source_income');
                            $id = $request->route('transaction') ?? $request->route('id') ?? $request->input('id');
                            $user = auth()->user();

                            $totalIncome = $user->transactions()
                                ->where('category_id', $sourceType)
                                ->where('type', 'income')
                                ->sum('amount');

                            $totalExpenses = $user->transactions()
                                ->where('source_income', $sourceType)
                                ->where('type', 'expenses')
                                ->where('id', '!=', $id)
                                ->sum('amount');

                            $totalSavings = $user->transactions()
                                ->where('category_id', $sourceType)
                                ->where('type', 'savings')
                                ->sum('amount');

                            $remainingIncome = $totalIncome - $totalExpenses - $totalSavings;


                            if ($value > $remainingIncome) {
                                $fail(
                                    'Amount exceeds remaining income for the selected category: ₱' 
                                    . number_format(max(0, $remainingIncome), 2) 
                                    . ' (includes this transaction).'
                                );
                            }
                        };
                    }
                ),

                // Savings from income
                Rule::when(
                    $request->input('type') === 'savings',
                    function () use ($request) {
                        return function ($attribute, $value, $fail) use ($request) {
                            $user = auth()->user();
                            $categoryId = $request->input('category_id');
                            $id = $request->route('transaction') ?? $request->route('id') ?? $request->input('id');

                            $totalIncome = $user->transactions()
                                ->where('category_id', $categoryId)
                                ->where('type', 'income')
                                ->sum('amount');

                            $totalExpenses = $user->transactions()
                                ->where('source_income', $categoryId)
                                ->where('type', 'expenses')
                                ->sum('amount');

                            $totalSavings = $user->transactions()
                                ->where('category_id', $categoryId)
                                ->where('id', '!=', $id)
                                ->where('type', 'savings')
                                ->sum('amount');

                            $remainingIncome = $totalIncome - $totalExpenses - $totalSavings;

                            if ($value > $remainingIncome) {
                                $fail(
                                    'Amount exceeds remaining income for the selected category: ₱' 
                                    . number_format(max(0, $remainingIncome), 2) 
                                    . ' (includes this transaction).'
                                );
                            }
                        };
                    }
                ),
            ],

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
        ];
    }

    

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator);
    }

}
