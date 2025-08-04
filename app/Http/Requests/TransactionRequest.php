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
            "amount"=> "required|numeric",
            "type" => 'required|in:income,expenses,savings',
            "notes" => "nullable|string|max:255",
            "category_id" => [
                'nullable',
                'exists:categories,id',
                'integer',
                Rule::requiredIf(in_array($this->input('type'), ['income', 'expenses'])),
            ],
            'savings_account_id' => [
                'nullable',
                'integer',
                'exists:savings_accounts,id',
                Rule::requiredIf($this->input('type') === 'savings'),
            ],
            'source_type' => [
                'nullable',
                'integer',
                Rule::requiredIf($this->input('type') === 'expenses'),
                Rule::when((int) $this->input('source_type') !== 0 && $this->input('type') === 'expenses', [
                'exists:savings_accounts,id',
                ]),
            ],

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
