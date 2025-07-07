<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
    protected const SOMETIMES_NULLABLE = 'sometimes|nullable';
    protected const SOMETIMES_NULLABLE_MAX_50 = 'sometimes|nullable|max:50';
    protected const SOMETIMES_NULLABLE_MAX_100 = 'sometimes|nullable|max:100';
    protected const SOMETIMES_NULLABLE_MAX_200 = 'sometimes|nullable|max:200';
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [];

        if ($this->section_id === 1) {
            $rules = [
                'section_title_one' => self::SOMETIMES_NULLABLE,
                'description_one' => self::SOMETIMES_NULLABLE,
                'label_one' => self::SOMETIMES_NULLABLE,
                'line_one' => self::SOMETIMES_NULLABLE,
                'line_two' => self::SOMETIMES_NULLABLE,
                'thumbnail_image_one' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        } elseif ($this->section_id === 29) {
            $rules = [
                'description_two' => self::SOMETIMES_NULLABLE,
                'label_two' => self::SOMETIMES_NULLABLE,
                'thumbnail_image_two' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        } elseif ($this->section_id === 42) {
            $rules = [
                'vehicle_id' => self::SOMETIMES_NULLABLE,
                'label_1' => self::SOMETIMES_NULLABLE_MAX_50,
                'dis_1' => self::SOMETIMES_NULLABLE_MAX_100,
                'label_2' => self::SOMETIMES_NULLABLE_MAX_50,
                'dis_2' => self::SOMETIMES_NULLABLE_MAX_100,
                'label_3' => self::SOMETIMES_NULLABLE_MAX_50,
                'dis_3' => self::SOMETIMES_NULLABLE_MAX_100,
                'label_4' => self::SOMETIMES_NULLABLE_MAX_50,
                'dis_4' => self::SOMETIMES_NULLABLE_MAX_100,
                'label_5' => self::SOMETIMES_NULLABLE_MAX_50,
                'dis_5' => self::SOMETIMES_NULLABLE_MAX_100,
                'label_6' => self::SOMETIMES_NULLABLE_MAX_50,
                'dis_6' => self::SOMETIMES_NULLABLE_MAX_100,
            ];
        } elseif ($this->section_id === 26) {
            $rules = [
                'why_label_1' => self::SOMETIMES_NULLABLE_MAX_50,
                'why_dis_1' => self::SOMETIMES_NULLABLE_MAX_200,
                'why_label_2' => self::SOMETIMES_NULLABLE_MAX_50,
                'why_dis_2' => self::SOMETIMES_NULLABLE_MAX_200,
                'why_label_3' => self::SOMETIMES_NULLABLE_MAX_50,
                'why_dis_3' => self::SOMETIMES_NULLABLE_MAX_200,
                'why_icon_1' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'why_icon_2' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'why_icon_3' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nullable' => __('The :attribute field is nullable.'),
            'max' => __('The :attribute may not be greater than :max characters.'),
            'image' => __('The :attribute must be an image.'),
            'mimes' => __('The :attribute must be a file of type: :values.'),
        ];
    }
}
