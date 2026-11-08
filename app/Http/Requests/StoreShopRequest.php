<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_name'              => ['required', 'string', 'max:255'],
            'owner_name'             => ['required', 'string', 'max:255'],
            'owner_phone'            => ['required', 'string', 'max:20'],
            'owner_email'            => ['nullable', 'email', 'max:255'],
            'description'            => ['nullable', 'string'],
            'is_active'              => ['sometimes', 'boolean'],
            'branches'               => ['required', 'array', 'list', 'min:1'],
            'branches.*'             => ['required', 'array:id,branch_name,address,phone,latitude,longitude,is_active'],
            'branches.*.id'          => ['sometimes', 'integer', 'min:1', 'distinct'],
            'branches.*.branch_name' => ['required', 'string', 'max:255'],
            'branches.*.address'     => ['required', 'string', 'max:500'],
            'branches.*.phone'       => ['nullable', 'string', 'max:20'],
            'branches.*.latitude'    => ['nullable', 'required_with:branches.*.longitude', 'numeric', 'between:-90,90'],
            'branches.*.longitude'   => ['nullable', 'required_with:branches.*.latitude', 'numeric', 'between:-180,180'],
            'branches.*.is_active'   => ['sometimes', 'boolean'],
        ];
    }
}
