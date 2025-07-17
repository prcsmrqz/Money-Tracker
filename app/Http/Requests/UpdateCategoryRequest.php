<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            $id = $this->route('category') ?? $this->route('id') ?? $this->input('id');

            return [
                "name_$id" => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('categories', 'name')
                    ->ignore($id)
                    ->where(function ($query) {
                        return $query->where('type', $this->type);
                    }),
            ],
                'type' => 'required',
                "iconEdit_$id" => 'nullable|mimes:png,jpg,jpeg,webp,svg|max:2048',
            ];
        }
        
        public function messages()
        {
            $id = $this->route('category') ?? $this->route('id') ?? $this->input('id');

            return [
                "name_$id.required" => 'The category name is required.',
                "name_$id.min" => 'The category name must be at least 3 characters.',
                "iconEdit_$id.mimes" => 'The icon must be an image file (jpg, png, jpeg, svg, or webp).',
                "iconEdit_$id.max" => 'The icon must not exceed 2MB in size.',
            ];
        }


}
