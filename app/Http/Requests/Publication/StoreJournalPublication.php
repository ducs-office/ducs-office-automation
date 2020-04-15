<?php

namespace App\Http\Requests\Publication;

use Illuminate\Foundation\Http\FormRequest;

class StoreJournalPublication extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $indexedIn = implode(',', array_keys(config('options.scholars.academic_details.indexed_in')));
        $months = implode(',', array_keys(config('options.scholars.academic_details.months')));

        return [
            'name' => ['required', 'string', 'max:400'],
            'authors' => ['required', 'array', 'max:10', 'min:1'],
            'authors.0' => ['required', 'string'],
            'paper_title' => ['required', 'string', 'max:400'],
            'date' => ['required', 'array', 'size:2'],
            'date.month' => ['required', 'in:' . $months],
            'date.year' => ['required', 'numeric'],
            'volume' => ['nullable', 'integer'],
            'publisher' => ['required', 'string', 'max:100'],
            'number' => ['nullable', 'numeric'],
            'indexed_in' => ['required', 'array'],
            'indexed_in.*' => ['in:' . $indexedIn],
            'page_numbers' => ['required', 'array', 'size:2'],
            'page_numbers.0' => ['required', 'integer'],
            'page_numbers.1' => ['required', 'integer', 'gte:page_numbers.0'],
        ];
    }
}
