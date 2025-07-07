<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;
use Illuminate\Http\UploadedFile;

class StoreMaintenanceSettingsRequest extends CustomFailedValidation
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
            'group_id' => 'required|integer',
            'maintenance_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'maintenance_description' => 'nullable|string|max:5000',
            'maintenance_status' => 'nullable',
            'is_remove_image' => 'nullable|boolean',
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{group_id: int, maintenance_image?: UploadedFile|null, is_remove_image?: bool|string}
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        return [
            'group_id' => (int) $validated['group_id'],
            'maintenance_image' => $this->file('maintenance_image'),
            'is_remove_image' => $validated['is_remove_image'] ?? null,
            // Include other fields if needed by the repository
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'group_id.required' => __('admin.general_settings.validation_failed'),
        ];
    }
}
