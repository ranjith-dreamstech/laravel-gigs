<?php

namespace Modules\RolesPermission\Http\Requests;

use App\Library\CustomFailedValidation;
use Illuminate\Validation\Rule;

class RoleRequest extends CustomFailedValidation
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = $this->input('id');
        /** @var \App\Models\User|null $user */
        $user = current_user();
        $authId = $user?->id;
        return [
            'role' => [
                'required',
                'min:3',
                'max:30',
                Rule::unique('roles', 'role_name')
                    ->ignore($id)
                    ->where(function ($query) use ($authId) {
                        $query->whereNull('deleted_at')->where('created_by', $authId);
                    }),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role.required' => __('admin.user_management.role_required'),
            'role.max' => __('admin.user_management.role_maxlength'),
            'role.min' => __('admin.user_management.role_minlength'),
            'role.unique' => __('admin.user_management.role_unique'),
        ];
    }
}
