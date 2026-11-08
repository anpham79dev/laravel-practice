<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'    => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'sort'      => ['sometimes', Rule::in(['shop_name', 'created_at'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page'  => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page'      => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
