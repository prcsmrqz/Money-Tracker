<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSavingsRequest extends FormRequest
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
            "name" => "required|max:255",
            "color" => "required",
            "type" => "required",
            "account_number" => "required",
            'icon' => 'nullable|mimes:png,jpg,jpeg,webp,svg|max:2048'

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $id = $this->route('savings') ?? $this->route('saving') ?? $this->input('saving');

        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator, 'update_' . $id)
                ->withInput()
        );
    }

}
