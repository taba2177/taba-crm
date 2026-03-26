<?php

namespace Taba\Crm\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => ['required', 'array'],
            'name.*'             => ['required', 'string', 'max:255'],
            'slug'               => ['required', 'string', 'max:255', 'unique:post_categories,slug'],
            'description'        => ['nullable', 'array'],
            'description.*'      => ['nullable', 'string'],
            'subtitle'           => ['nullable', 'array'],
            'subtitle.*'         => ['nullable', 'string', 'max:255'],
            'parent_id'          => ['nullable', 'integer', 'exists:post_categories,id'],
            'order'              => ['nullable', 'integer'],
            'register_in_header' => ['nullable', 'boolean'],
            'section_component'  => ['nullable', 'string', 'max:255'],
            'image'              => ['nullable', 'string', 'max:500'],
            'is_active'          => ['nullable', 'boolean'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
