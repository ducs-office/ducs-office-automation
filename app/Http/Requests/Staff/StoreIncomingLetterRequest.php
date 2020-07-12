<?php

namespace App\Http\Requests\Staff;

use App\Models\IncomingLetter;
use App\Types\Priority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIncomingLetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', IncomingLetter::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => ['required', 'date', 'before_or_equal:today'],
            'received_id' => ['required', 'string', 'min:3', 'max:190'],
            'sender' => ['required', 'string', 'min:5', 'max:100'],
            'recipient_id' => ['required', 'exists:users,id'],
            'handovers' => ['nullable', 'array'],
            'handovers.*' => ['integer', 'exists:users,id'],
            'priority' => ['nullable', Rule::in(Priority::values())],
            'subject' => ['required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'min:4', 'max:400'],
            'attachments' => ['required', 'array', 'min:1', 'max:2'],
            'attachments.*' => ['file', 'max:200', 'mimetypes:application/pdf,image/*'],
        ];
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
