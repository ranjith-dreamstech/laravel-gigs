<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStateRequest extends FormRequest
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
        $id = $this->id ?? null;

        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('states')->where(function ($query) use ($id) {
                    return $query->where('country_id', $this->country_id)
                        ->when($id, fn ($q) => $q->where('id', '!=', $id));
                }),
            ],
            'country_id' => [
                'required',
                'exists:countries,id',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'name.required' => __('admin.cms.state_required'),
            'name.unique' => __('admin.cms.state_exists'),
            'country_id.required' => __('admin.cms.country_required'),
            'country_id.exists' => __('admin.cms.country_exists'),
        ];
    }

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
