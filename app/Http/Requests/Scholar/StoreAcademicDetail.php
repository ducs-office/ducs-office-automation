<?php

namespace App\Http\Requests\Scholar;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicDetail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $indexedIn = implode(',', array_keys(config('options.scholars.academic_details.indexed_in')));

        return [
            'authors' => ['required', 'array', 'max:10', 'min:1'],
            'authors.*' => ['required', 'string'],
            'title' => ['required', 'string', 'max:400'],
            'conference' => ['required', 'string', 'max:100'],
            'volume' => ['nullable', 'integer'],
            'publisher' => ['required', 'string', 'max:100'],
            'page_numbers' => ['required', 'array', 'size:2'],
            'page_numbers.from' => ['required', 'integer'],
            'page_numbers.to' => ['required', 'integer', 'gte:page_numbers.from'],
            'date' => ['required', 'date'],
            'number' => ['nullable', 'numeric'],
            'venue' => ['required', 'array', 'size:2'],
            'venue.city' => ['required', 'string'],
            'venue.country' => ['required', 'string'],
            'indexed_in' => ['required', 'array'],
            'indexed_in.*' => ['in:' . $indexedIn],
        ];
    }
}
