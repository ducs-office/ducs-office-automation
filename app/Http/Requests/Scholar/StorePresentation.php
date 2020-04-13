<?php

namespace App\Http\Requests\Scholar;

use App\Types\PresentationEventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePresentation extends FormRequest
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
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
            'date' => ['required', 'date'],
            'event_type' => ['required', Rule::in(PresentationEventType::values())],
            'event_name' => ['required', 'string'],
            'publication_id' => ['required', 'in:' . $ids],
            'scopus_indexed' => ['accepted'],
        ];
    }
}
