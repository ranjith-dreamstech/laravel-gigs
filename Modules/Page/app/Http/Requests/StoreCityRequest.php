<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
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
                Rule::unique('cities')->where(function ($query) use ($id) {
                    return $query->where('state_id', $this->state_id)
                        ->when($id, function ($q) use ($id) {
                            return $q->where('id', '!=', $id);
                        });
                }),
            ],
            'state_id' => [
                'required',
                'exists:states,id',
            ],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('admin.cms.city_required'),
            'name.unique' => __('admin.cms.city_exists'),
            'state_id.required' => __('admin.cms.state_required'),
            'state_id.exists' => __('admin.cms.state_exists'),
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
