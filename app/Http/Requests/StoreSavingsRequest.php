<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreSavingsRequest extends FormRequest
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
        $remainingIncome = 0;

        if (auth()->check()) {
            $user = auth()->user();

            $expenses = $user->transactions()
                ->where('source_type', 0)
                ->where('type', 'expenses')
                ->sum('amount');

            $savings = $user->transactions()
                ->where('type', 'savings')
                ->sum('amount');

            $income = $user->transactions()
                ->where('type', 'income')
                ->sum('amount');

            $remainingIncome = $income - $expenses - $savings;
        }

        return [
            "name" => "required|max:255",
            "color" => "required",
            "type" => "required",
            "account_number" => "required",
            'icon' => 'nullable|mimes:png,jpg,jpeg,webp,svg|max:2048',
            "date" => "required|date",
            "transaction_type" => 'required|in:income,expenses,savings',
            "amount" => [
                'required',
                'numeric',
                'min:1',
                'max:' . $remainingIncome,
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator, 'create')
                ->withInput()
        );
    }

    
}
