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
        return [
            'authors' => ['required', 'array', 'max:10'],
            'title' => ['required', 'string', 'max:400'],
            'conference' => ['required', 'string', 'max:100'],
            'volume' => ['nullable', 'integer'],
            'publisher' => ['required', 'string', 'max:100'],
            'page_numbers' => ['required', 'array', 'size:2'],
            'date' => ['required', 'date'],
            'number' => ['nullable', 'numeric'],
            'venue' => ['required', 'array', 'size:2'],
            'indexed_in' => ['required', 'array'],
            // 'indexed_in.*' => ['in:Scopus,SCI,SCIE'],
        ];
    }
}
