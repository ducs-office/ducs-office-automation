<?php

namespace App\Http\Requests\Scholar;

use Illuminate\Foundation\Http\FormRequest;

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
        $scholar = $this->user();
        $publications = $scholar->publications();

        $ids = implode(',', $publications->pluck('id')->toArray());

        $event_types = implode(',', array_keys(config('options.scholars.academic_details.event_types')));
        return [
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
            'date' => ['required', 'date'],
            'event_type' => ['required', 'in:' . $event_types],
            'event_name' => ['required', 'string'],
            'publication_id' => ['required', 'in:' . $ids],
            'scopus_indexed' => ['accepted'],
        ];
    }
}
