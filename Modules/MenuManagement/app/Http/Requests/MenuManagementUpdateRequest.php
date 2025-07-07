<?php

namespace Modules\MenuManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuManagementUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'menu_id' => 'required|exists:menus,id',
            'menu_items' => 'required|array|min:1',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'menu_id.required' => __('Menu ID is required'),
            'menu_id.exists' => __('Selected menu does not exist'),
            'menu_items.required' => __('Menu items are required'),
            'menu_items.array' => __('Menu items must be an array'),
            'menu_items.min' => __('At least one menu item is required'),
            'menu_items.*.link.required' => __('Link is required for all menu items'),
        ];
    }
}
