<?php

namespace App\Http\Requests\Api\V1\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'products' => ['required', 'array'],
            'products.*' => ['required', 'array'],
            'products.*.product_id' => ['required', 'integer', Rule::exists('products', 'id'), 'distinct:products.*.product_id',],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
