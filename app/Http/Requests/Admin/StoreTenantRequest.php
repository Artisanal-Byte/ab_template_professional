<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreTenantRequest extends FormRequest
{
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
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9_]+$/', Rule::unique('tenants', 'slug')],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
            ],
            'owner_password' => ['nullable', 'string', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Organization name is required.',
            'slug.regex' => 'Slug can only contain lowercase letters, numbers, and underscores.',
            'owner_name.required' => 'Owner name is required.',
            'owner_email.required' => 'Owner email is required.',
        ];
    }
}
