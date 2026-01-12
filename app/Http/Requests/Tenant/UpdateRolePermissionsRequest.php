<?php

namespace App\Http\Requests\Tenant;

use App\Support\CurrentTenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tenant = app(CurrentTenant::class)->get();

        return [
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'integer',
                Rule::exists('permissions', 'id')
                    ->where('tenant_id', $tenant?->id),
            ],
        ];
    }
}
