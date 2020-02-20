<?php

namespace App\Http\Requests\Staff;

use App\IncomingLetter;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIncomingLettersRequest extends FormRequest
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
        $priorities = implode(',', array_keys(config('options.incoming_letters.priorities')));

        $rules = [
            'date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'received_id' => ['sometimes', 'required', 'string', 'min:3', 'max:190'],
            'sender' => ['sometimes', 'required', 'string', 'min:5', 'max:100'],
            'recipient_id' => ['sometimes', 'required', 'exists:users,id'],
            'handovers' => ['sometimes', 'nullable', 'array'],
            'handovers.*' => ['integer', 'exists:users,id'],
            'priority' => ['nullable', 'in:' . $priorities],
            'subject' => ['sometimes', 'required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'max:400'],
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

    public function attachmentFiles()
    {
        return array_map(static function ($attachedFile) {
            return [
                'original_name' => $attachedFile->getClientOriginalName(),
                'path' => $attachedFile->store('/letter_attachments/incoming'),
            ];
        }, $this->file('attachments') ?? []);
    }
}
