<?php

namespace App\Http\Requests\Brand;

use App\Policies\BrandPolicy;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Override;

class StoreBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', BrandPolicy::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'slug' => ['required', 'string', Rule::unique('brand', 'slug')],
            'image' => ['nullable', 'image', 'mimes:png,jpg,webp,jpeg', 'max:2048']
        ];
    }

    #[Override]
    protected function prepareForValidation()
    {
        return $this->merge([
            'slug' => $this->input('slug') ?: Str::slug($this->input('name'))
        ]);
    }
}
