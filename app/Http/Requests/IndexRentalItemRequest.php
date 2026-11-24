<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRentalItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search'      => ['nullable', 'string', 'max:255'],
            'category_id' => ['sometimes', 'integer', Rule::exists('categories', 'id')],
            'shop_id'     => ['sometimes', 'integer', 'min:1'],
            'sort'        => ['sometimes', Rule::in(['created_at', 'rental_price_per_day', 'item_name'])],
            'direction'   => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page'    => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page'        => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
