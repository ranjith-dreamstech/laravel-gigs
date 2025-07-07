<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class SitemapSettingRequest extends CustomFailedValidation
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
        return [
            'id' => ['nullable', 'exists:sitemap_urls,id'],
            'url' => [
                'required',
                'string',
                'max:200',
                $this->id
                    ? 'unique:sitemap_urls,url,' . $this->id . ',id'
                    : 'unique:sitemap_urls,url',
                'regex:/^(https?:\/\/)(localhost|(\d{1,3}\.){3}\d{1,3}|([a-zA-Z0-9.-]+\.[a-zA-Z]{2,}))(:\d+)?(\/.*)?$/',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'url.unique' => __('admin.general_settings.url_added'),
        ];
    }
}
