<?php

namespace App\Http\Requests\Scholar;

use App\Types\PresentationEventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePresentation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ids = $this->user()->publications->pluck('id')->implode(',');

        return [
            'city' => ['sometimes', 'required', 'string'],
            'country' => ['sometimes', 'required', 'string'],
            'date' => ['sometimes', 'required', 'date'],
            'event_type' => ['sometimes', 'required', Rule::in(PresentationEventType::values())],
            'event_name' => ['sometimes', 'required', 'string'],
            'publication_id' => ['sometimes', 'required', 'in:' . $ids],
        ];
    }
}
