<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
           'name' => [
            'required',
            'string',
            'max:255',
            'min:3',
            Rule::unique('categories')->where(function ($query) {
                        return $query->where('type', $this->type);
            }),
        ],
            'type' => 'required',
            'icon' => 'nullable|mimes:png,jpg,jpeg,webp,svg|max:2048',
        ];
    }


}