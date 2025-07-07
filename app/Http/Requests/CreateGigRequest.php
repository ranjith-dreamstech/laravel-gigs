<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateGigRequest extends FormRequest
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
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'min:10'
            ],
            'description' => [
                'required',
                'string',
                'min:50',
                'max:5000'
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id'
            ],
            'subcategory_id' => [
                'nullable',
                'integer',
                'exists:categories,id'
            ],
            'price' => [
                'required',
                'numeric',
                'min:1',
                'max:999999.99'
            ],
            'delivery_time' => [
                'required',
                'integer',
                'min:1',
                'max:365'
            ],
            'tags' => [
                'nullable',
                'array',
                'max:10'
            ],
            'tags.*' => [
                'string',
                'max:50'
            ],
            'images' => [
                'required',
                'array',
                'min:1',
                'max:5'
            ],
            'images.*' => [
                'file',
                'mimes:jpeg,jpg,png,webp',
                'max:10240', // 10MB
                'dimensions:min_width=800,min_height=600'
            ],
            'extras' => [
                'nullable',
                'array',
                'max:5'
            ],
            'extras.*.title' => [
                'required_with:extras',
                'string',
                'max:255'
            ],
            'extras.*.price' => [
                'required_with:extras',
                'numeric',
                'min:1'
            ],
            'extras.*.delivery_time' => [
                'required_with:extras',
                'integer',
                'min:1'
            ],
            'faq' => [
                'nullable',
                'array',
                'max:10'
            ],
            'faq.*.question' => [
                'required_with:faq',
                'string',
                'max:500'
            ],
            'faq.*.answer' => [
                'required_with:faq',
                'string',
                'max:1000'
            ],
            'requirements' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'status' => [
                'nullable',
                'integer',
                Rule::in([0, 1])
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Gig title is required.',
            'title.min' => 'Gig title must be at least 10 characters long.',
            'title.max' => 'Gig title cannot exceed 255 characters.',
            'description.required' => 'Gig description is required.',
            'description.min' => 'Gig description must be at least 50 characters long.',
            'description.max' => 'Gig description cannot exceed 5000 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price must be at least $1.',
            'price.max' => 'Price cannot exceed $999,999.99.',
            'delivery_time.required' => 'Delivery time is required.',
            'delivery_time.min' => 'Delivery time must be at least 1 day.',
            'delivery_time.max' => 'Delivery time cannot exceed 365 days.',
            'images.required' => 'At least one image is required.',
            'images.min' => 'Please upload at least one image.',
            'images.max' => 'You can upload maximum 5 images.',
            'images.*.mimes' => 'Images must be in JPEG, JPG, PNG, or WebP format.',
            'images.*.max' => 'Each image must not exceed 10MB.',
            'images.*.dimensions' => 'Images must be at least 800x600 pixels.',
            'tags.max' => 'You can add maximum 10 tags.',
            'tags.*.max' => 'Each tag cannot exceed 50 characters.',
            'extras.max' => 'You can add maximum 5 extra services.',
            'faq.max' => 'You can add maximum 10 FAQ items.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'subcategory_id' => 'subcategory',
            'delivery_time' => 'delivery time',
            'extras.*.title' => 'extra service title',
            'extras.*.price' => 'extra service price',
            'extras.*.delivery_time' => 'extra service delivery time',
            'faq.*.question' => 'FAQ question',
            'faq.*.answer' => 'FAQ answer',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert price to float
        if ($this->has('price')) {
            $this->merge([
                'price' => (float) str_replace(',', '', $this->price)
            ]);
        }

        // Convert delivery_time to integer
        if ($this->has('delivery_time')) {
            $this->merge([
                'delivery_time' => (int) $this->delivery_time
            ]);
        }

        // Clean and prepare tags
        if ($this->has('tags') && is_string($this->tags)) {
            $tags = explode(',', $this->tags);
            $cleanTags = array_map('trim', $tags);
            $this->merge(['tags' => array_filter($cleanTags)]);
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        if ($this->wantsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'timestamp' => now()->toISOString(),
            ], 422);

            throw new \Illuminate\Validation\ValidationException($validator, $response);
        }

        parent::failedValidation($validator);
    }
}