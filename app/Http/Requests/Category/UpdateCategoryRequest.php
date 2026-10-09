<?php

namespace App\Http\Requests\Category;

use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Category::class) ?? false;;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');
        $category_id = $category->id;
        return [
            'name' => ['required', 'string', 'max:55'],
            'slug' => ['required', 'max:55', Rule::unique('categories', 'slug')->ignore($category_id)],
            'image' => ['nullable', 'mimes:png,jpg,webp,jpeg', 'max:4028'],
        ];
    }
}
