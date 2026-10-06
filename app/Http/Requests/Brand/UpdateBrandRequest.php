<?php

namespace App\Http\Requests\Brand;

use App\Models\Brand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', Brand::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $brand = $this->route('brand');
        $brand_id = $brand->id;
        return [
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['required', Rule::unique('brands', 'slug')->ignore($brand_id)],
            'image' => ['nullable', 'mimes:png,jpg,webp,jpeg', 'max:4028']
        ];
    }
}
