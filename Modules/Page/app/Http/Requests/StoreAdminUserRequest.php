<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->id ?? '';

        $rules = [
            'first_name' => [
                'required',
                'min:3',
                'max:20',
            ],
            'last_name' => [
                'required',
                'max:20',
            ],
            'phone_number' => ['required'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id)->whereNull('deleted_at'),
            ],
            'image' => 'mimes:jpeg,jpg,png|max:2048',
            'role_id' => ['required'],
            'password' => ['nullable'],
            'confirm_password' => ['nullable', 'same:password'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];

        // Make password required for new users
        if (empty($id)) {
            $rules['password'] = ['required', 'min:6'];
            $rules['confirm_password'] = ['required', 'same:password'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => __('admin.common.first_name_required'),
            'first_name.min' => __('admin.common.first_name_minlength', ['min' => 3]),
            'first_name.max' => __('admin.common.first_name_maxlength', ['max' => 30]),
            'last_name.required' => __('admin.common.last_name_required'),
            'last_name.min' => __('admin.common.last_name_minlength', ['min' => 3]),
            'last_name.max' => __('admin.common.last_name_maxlength', ['max' => 30]),
            'username.required' => __('admin.common.username_required'),
            'username.max' => __('admin.common.username_maxlength'),
            'username.unique' => __('admin.common.username_unique'),
            'phone_number.required' => __('admin.common.phone_number_required'),
            'image.mimes' => __('admin.common.image_format'),
            'image.max' => __('admin.common.image_size', ['size' => 2]),
            'email.required' => __('admin.common.email_required'),
            'email.email' => __('admin.common.email_valid'),
            'email.unique' => __('admin.common.email_unique'),
            'role_id.required' => __('admin.user_management.role_required'),
            'password.required' => __('admin.common.password_required'),
            'password.min' => __('admin.common.password_minlength', ['min' => 6]),
            'confirm_password.required' => __('admin.common.confirm_password_required'),
            'confirm_password.same' => __('admin.common.confirm_password_equal_to'),
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
            'first_name' => __('admin.common.first_name'),
            'last_name' => __('admin.common.last_name'),
            'phone_number' => __('admin.common.phone_number'),
            'email' => __('admin.common.email'),
            'image' => __('admin.common.image'),
            'role_id' => __('admin.common.role'),
            'password' => __('admin.common.password'),
            'confirm_password' => __('admin.common.confirm_password'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'status' => 'error',
                'code' => 422,
                'errors' => $validator->errors()->toArray(),
            ], 422)
        );
    }
}
