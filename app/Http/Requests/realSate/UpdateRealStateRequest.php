<?php

namespace App\Http\Requests\realSate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRealStateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "title" => 'string',
            "description" => 'string',
            "content" => 'string',
            "price" => 'numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            "bathrooms" => 'numeric',
            "bedrooms" => 'numeric',
            "property_area" => 'numeric',
            "total_property_area" => 'numeric',
            "categories" => 'array'
        ];
    }
}
