<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class StoreCookiesSettingsRequest extends CustomFailedValidation
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
            'language' => 'required|integer',
            'cookiesContentText' => 'required|string|max:5000',
            'cookiesPosition' => 'required|in:right,left',
            'agreeButtonText' => 'required|string|min:2|max:255',
            'declineButtonText' => 'required|string|min:2|max:255',
            'showDeclineButton' => 'nullable|boolean',
            'cookiesPageLink' => 'required|url|max:2048',
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{
     *     cookiesContentText: string,
     *     cookiesPosition: string,
     *     agreeButtonText: string,
     *     declineButtonText: string,
     *     showDeclineButton?: bool,
     *     cookiesPageLink: string,
     *     language: int,
     *     group_id: int
     * }
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        return [
            'cookiesContentText' => (string) $validated['cookiesContentText'],
            'cookiesPosition' => (string) $validated['cookiesPosition'],
            'agreeButtonText' => (string) $validated['agreeButtonText'],
            'declineButtonText' => (string) $validated['declineButtonText'],
            'showDeclineButton' => $validated['showDeclineButton'] ?? null,
            'cookiesPageLink' => (string) $validated['cookiesPageLink'],
            'language' => (int) $validated['language'],
            'group_id' => (int) $validated['group_id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'group_id.required' => __('The group ID is required.'),
            'language.required' => __('The language ID is required.'),
            'cookiesContentText.required' => __('Cookies content is required.'),
            'cookiesPosition.in' => __('Position must be either "right" or "left".'),
            'agreeButtonText.required' => __('Agree button text is required.'),
            'declineButtonText.required' => __('Decline button text is required.'),
            'cookiesPageLink.url' => __('The cookies page link must be a valid URL.'),
        ];
    }
}
