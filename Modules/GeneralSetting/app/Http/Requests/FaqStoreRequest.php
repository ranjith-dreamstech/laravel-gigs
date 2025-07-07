<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class FaqStoreRequest extends CustomFailedValidation
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'question' => 'required|string|max:255|unique:faqs,question',
            'answer' => 'required|string',
            'language' => 'required|integer|exists:translation_languages,id',
            'status' => 'nullable|boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'question.required' => __('admin.cms.question_required'),
            'question.unique' => __('admin.cms.question_unique'),
            'answer.required' => __('admin.cms.answer_required'),
            'language.required' => __('admin.cms.language_required'),
            'language.exists' => __('admin.cms.language_exists'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
