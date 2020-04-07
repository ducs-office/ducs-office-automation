<?php

namespace App\Http\Requests\Scholar;

use Illuminate\Foundation\Http\FormRequest;

class StoreConferencePublication extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $indexedIn = implode(',', array_keys(config('options.scholars.academic_details.indexed_in')));

        return [
            'name' => ['required', 'string', 'max:400'],
            'authors' => ['required', 'array', 'max:10', 'min:1'],
            'authors.0' => ['required', 'string'],
            'paper_title' => ['required', 'string', 'max:400'],
            'date' => ['required', 'date'],
            'volume' => ['nullable', 'integer'],
            'indexed_in' => ['required', 'array'],
            'indexed_in.*' => ['in:' . $indexedIn],
            'page_numbers' => ['required', 'array', 'size:2'],
            'page_numbers.0' => ['required', 'integer'],
            'page_numbers.1' => ['required', 'integer', 'gte:page_numbers.0'],
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
        ];
    }
}
