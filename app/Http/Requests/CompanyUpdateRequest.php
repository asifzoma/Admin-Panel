<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow if user is authenticated and is admin
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $companyId = $this->route('company');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,'.$companyId,
            'address' => 'required|string|max:255',
            'website' => 'nullable|url',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100',
        ];
    }
}
