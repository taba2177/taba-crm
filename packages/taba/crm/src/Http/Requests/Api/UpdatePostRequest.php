<?php

namespace Taba\Crm\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postId = $this->route('post')?->id ?? $this->route('post');

        return [
            'title'                => ['sometimes', 'array'],
            'title.*'              => ['required', 'string', 'max:255'],
            'slug'                 => ['sometimes', 'string', 'max:255', 'unique:posts,slug,' . $postId],
            'content'              => ['sometimes', 'array'],
            'content.*'            => ['nullable', 'string'],
            'meta_title'           => ['nullable', 'array'],
            'meta_title.*'         => ['nullable', 'string', 'max:255'],
            'meta_description'     => ['nullable', 'array'],
            'meta_description.*'   => ['nullable', 'string', 'max:500'],
            'metadata'             => ['nullable', 'array'],
            'icon'                 => ['nullable', 'string', 'max:255'],
            'image_id'             => ['nullable', 'integer', 'exists:media,id'],
            'post_category_id'     => ['sometimes', 'integer', 'exists:post_categories,id'],
            'is_published'         => ['boolean'],
            'published_at'         => ['nullable', 'date'],
            'show_in_home'         => ['nullable', 'boolean'],
            'order'                => ['nullable', 'integer'],
            'tags'                 => ['nullable', 'array'],
            'tags.*'               => ['integer', 'exists:tags,id'],
            'homepage_section_component' => ['nullable', 'string', 'max:255'],
            'homepage_section_content'   => ['nullable', 'array'],
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
