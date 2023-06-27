<?php

namespace App\Http\Requests\userProfile;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserProfileRequest extends FormRequest
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
            'about' => 'required|string',
            'social_networks' => 'required|string',
            'phone' => 'required|string',
            'mobile_phone' => 'required|string'
        ];
    }
}
