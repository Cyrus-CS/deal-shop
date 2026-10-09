<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', Product::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        $product_id = $product ? $product->id : null;
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'max:120', Rule::unique('products', 'slug')->ignore($product_id)],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'regular_price' => ['required', 'decimal:0,2'],
            'sale_price' => ['nullable', 'decimal:0,2'],
            'stock_status' => ['required', 'in:instock,outofstock'],
            'image' => ['nullable', 'mimes:png,jpg,webp,jpeg', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['mimes:png,jpg,webp,jpeg', 'max:2048'],
            'featured' => ['nullable', 'boolean'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'quantity'    => ['required', 'integer', 'min:0'],
            'SKU' => ['nullable', 'string', 'max:50', Rule::unique('products', 'SKU')->ignore($product_id)],
        ];
    }
}
