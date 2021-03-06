<?php

namespace App\Http\Requests\Staff;

use App\Models\OutgoingLetter;
use App\Types\OutgoingLetterType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOutgoingLetterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', OutgoingLetter::class);
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
            'type' => ['required', Rule::in(OutgoingLetterType::values())],
            'recipient' => ['required', 'min:5', 'max:100'],
            'sender_id' => ['required', 'integer', 'exists:users,id'],
            'subject' => ['required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'max:400'],
            'amount' => ['nullable', 'numeric'],
            'attachments' => ['required', 'array', 'min:1', 'max:2'],
            'attachments.*' => ['file', 'max:200', 'mimetypes:application/pdf,image/*'],
        ];
    }
}
