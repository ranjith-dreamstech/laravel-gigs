<?php

namespace Modules\Communication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
            'ticketid' => 'required|exists:tickets,id',
            'status' => 'required|in:1,2,3,4',
            'reply' => [
                'required',
                'string',
                function ($value, $fail) {
                    if (str_word_count($value) > 60) {
                        $fail(__('admin.support.reply_maxwords'));
                    }
                },
            ],
        ];
    }
}
