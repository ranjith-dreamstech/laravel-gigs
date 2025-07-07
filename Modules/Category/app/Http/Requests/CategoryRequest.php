<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array<string, string> The validation rules for the category request.
     */
    public function rules()
    {
        $isUpdate = $this->has('id') && ! empty($this->id);
        $categoryId = $this->id;
        return [
            'categoryname' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:categories,slug' . ($isUpdate ? ',' . $categoryId : ''),
            'description' => 'required|string|max:255',
            'image' => $isUpdate ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'icon' => $isUpdate ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'feature' => 'nullable|in:on,1,true',
        ];
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'categoryname.required' => __('admin.common.name_required'),
            'slug.required' => __('admin.common.slug_required'),
            'slug.unique' => __('admin.common.slug_unique'),
            'description.required' => __('admin.common.description_required'),
            'image.required' => __('admin.common.image_required'),
            'icon.required' => __('admin.common.icon_required'),
            'image.image' => __('admin.common.image_format'),
            'icon.image' => __('admin.common.image_format'),
        ];
    }
}
