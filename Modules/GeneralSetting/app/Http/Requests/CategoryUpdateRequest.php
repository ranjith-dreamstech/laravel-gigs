<?php

namespace Modules\GeneralSetting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $this->id,
            'status' => 'boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('admin.manage.name_required'),
            'name.max' => __('admin.manage.name_maxlength'),
            'name.min' => __('admin.manage.name_minlength'),
            'name.unique' => __('admin.manage.name_unique'),
        ];
    }
}
