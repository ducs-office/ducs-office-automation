<?php

namespace App\Http\Requests\Scholar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJournalPublication extends FormRequest
{
    public function rules()
    {
        $indexedIn = implode(',', array_keys(config('options.scholars.academic_details.indexed_in')));
        $months = implode(',', array_keys(config('options.scholars.academic_details.months')));

        return [
            'authors' => ['sometimes', 'required', 'array', 'max:10', 'min:1'],
            'authors.0' => ['sometimes', 'required', 'string'],
            'paper_title' => ['sometimes', 'required', 'string', 'max:400'],
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'volume' => ['nullable', 'integer'],
            'publisher' => ['sometimes', 'required', 'string', 'max:100'],
            'page_numbers' => ['sometimes', 'required', 'array', 'size:2'],
            'page_numbers.0' => ['sometimes', 'required', 'integer'],
            'page_numbers.1' => ['sometimes', 'required', 'integer', 'gte:page_numbers.0'],
            'date' => ['sometimes', 'required', 'array', 'size:2'],
            'date.month' => ['sometimes', 'required', 'in:' . $months],
            'date.year' => ['sometimes', 'required', 'numeric'],
            'number' => ['nullable', 'numeric'],
            'indexed_in' => ['sometimes', 'required', 'array'],
            'indexed_in.*' => ['in:' . $indexedIn],
        ];
    }
}
