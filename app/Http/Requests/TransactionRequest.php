<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "date" => "required|date",
            'amount' => [
                'required',
                'numeric',
                Rule::when( $this->input('type') === 'expenses' && $this->input('source_type') === 'savings',
                    function () {
                        return function ($attribute, $value, $fail) {
                            $sourceType = $this->input('source_savings');
                            $user = auth()->user();

                            $totalSavings = $user->transactions()
                                ->where('savings_account_id', $sourceType)
                                ->where('type', 'savings')
                                ->sum('amount');

                            $totalExpenses = $user->transactions()
                                ->where('source_savings', $sourceType)
                                ->where('type', 'expenses')
                                ->sum('amount');

                            $remainingSavings = $totalSavings - $totalExpenses;

                            if ($value > $remainingSavings) {
                                $fail('The amount exceeds the remaining savings (₱' . number_format($remainingSavings, 2) . ').');
                            }
                        };
                    }
                ),
                Rule::when( $this->input('type') === 'expenses' && $this->input('source_type') === 'income',
                    function () {
                        return function ($attribute, $value, $fail) {
                            $sourceType = $this->input('source_income');
                            $user = auth()->user();

                            $totalIncome = $user->transactions()
                                ->where('category_id', $sourceType)
                                ->where('type', 'income')
                                ->sum('amount');

                            $totalExpenses = $user->transactions()
                                ->where('source_income', $sourceType)
                                ->where('type', 'expenses')
                                ->sum('amount');

                            $totalSavings = $user->transactions()
                                ->where('type', 'savings')
                                ->sum('amount');

                            $remainingIncome = $totalIncome - $totalExpenses - $totalSavings;

                            if ($value > $remainingIncome) {
                                $fail('The amount exceeds the remaining income (₱' . number_format($remainingIncome, 2) . ').');
                            }
                        };
                    }
                ),
                Rule::when( $this->input('type') === 'savings',
                    function () {
                        return function ($attribute, $value, $fail) {
                            $user = auth()->user();

                            $totalIncome = $user->transactions()
                                ->where('category_id', $this->input('category_id'))
                                ->where('type', 'income')
                                ->sum('amount');

                            $totalExpenses = $user->transactions()
                                ->where('type', 'expenses')
                                ->sum('amount');
                            
                            $totalSavings = $user->transactions()
                                ->where('type', 'savings')
                                ->sum('amount');

                            $remainingIncome = $totalIncome - $totalExpenses - $totalSavings;

                            if ($value > $remainingIncome) {
                                $fail('Amount exceeds remaining income for the selected category (Remaining Income: ₱' . number_format(max(0, $remainingIncome), 2) . ').');
                            }
                        };
                    }
                ),
            ],
            "type" => 'required|in:income,expenses,savings',
            "notes" => "nullable|string|max:255",
            "category_id" => [
                'nullable',
                'exists:categories,id',
                'integer',
                'required'
            ],
            'savings_account_id' => [
                'nullable',
                'integer',
                'exists:savings_accounts,id',
                Rule::requiredIf($this->input('type') === 'savings'),
            ],
            'source_savings' => Rule::when(
                $this->input('type') === 'expenses' && $this->input('source_type') === 'savings',
                [
                    'required',
                    'integer',
                    Rule::exists('savings_accounts', 'id'),
                ],
                ['nullable']
            ),
            'source_income' => Rule::when(
                $this->input('type') === 'expenses' && $this->input('source_type') === 'income',
                [
                    'required',
                    'integer',
                    Rule::exists('categories', 'id')->where('type', 'income'),
                ],
                ['nullable']
            ),


        ];
    }
    public function messages(): array
    {
        return [
            'source_income.required_if' => 'The source type field is required.',
            'source_savings.required_if' => 'The source type field is required.'
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $type = request('type', 'default');

        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator, "{$type}Form")
                ->withInput()
                ->with('activeTab', $type)
        );
    }
}
