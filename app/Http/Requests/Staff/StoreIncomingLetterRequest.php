<?php

namespace App\Http\Requests\Staff;

use App\IncomingLetter;
use Illuminate\Foundation\Http\FormRequest;

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
        $priorities = implode(',', array_keys(config('options.incoming_letters.priorities')));

        return [
            'date' => 'required|date|before_or_equal:today',
            'received_id' => 'required|string|min:3|max:190',
            'sender' => 'required|string|min:5|max:100',
            'recipient_id' => 'required|exists:users,id',
            'handovers' => 'nullable|array',
            'handovers.*' => 'integer|exists:users,id',
            'priority' => 'nullable|in:' . $priorities,
            'subject' => 'required|string|min:5|max:100',
            'description' => 'nullable|string|min:4|max:400',
            'attachments' => 'required|array|min:1|max:2',
            'attachments.*' => 'file|max:200|mimes:jpeg,jpg,png,pdf',
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
