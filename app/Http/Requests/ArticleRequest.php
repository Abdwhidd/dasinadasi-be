<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles,slug,' . $this->article,
            'body' => 'required|string',
            'excerpt' => 'nullable|string|max:255',
            'cover_image' => 'nullable|string',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['title'] = 'nullable|string|max:255';
            $rules['slug'] = [
                'nullable',
                'string',
                Rule::unique('articles')->ignore($this->article)
            ];
            $rules['body'] = 'nullable|string';
            $rules['excerpt'] = 'nullable|string|max:255';
            $rules['cover_image'] = 'nullable|string';
        }

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
