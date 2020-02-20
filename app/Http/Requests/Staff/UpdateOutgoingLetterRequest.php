<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOutgoingLetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('letter'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'recipient' => ['sometimes', 'required', 'min:5', 'max:100'],
            'subject' => ['sometimes', 'required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'max:400'],
            'amount' => ['nullable', 'numeric'],
            'sender_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'attachments' => ['required', 'array', 'max:2'],
            'attachments.*' => ['file', 'max:200', 'mimes:jpeg,jpg,png,pdf'],
        ];

        if ($this->route('letter')->attachments()->count() < 1) {
            array_push($rules['attachments'], 'min:1');
        } else {
            array_unshift($rules['attachments'], 'sometimes');
        }

        return $rules;
    }
}
