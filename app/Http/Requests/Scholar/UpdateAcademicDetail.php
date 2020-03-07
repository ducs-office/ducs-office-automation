<?php

namespace App\Http\Requests\Scholar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicDetail extends FormRequest
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
            'authors' => ['sometimes', 'required', 'array', 'max:10'],
            'title' => ['sometimes', 'required', 'string', 'max:400'],
            'conference' => ['sometimes', 'required', 'string', 'max:100'],
            'volume' => ['nullable', 'integer'],
            'publisher' => ['sometimes', 'required', 'string', 'max:100'],
            'page_numbers' => ['sometimes', 'required', 'array', 'size:2'],
            'date' => ['sometimes', 'required', 'date'],
            'number' => ['nullable', 'numeric'],
            'venue' => ['sometimes', 'required', 'array', 'size:2'],
            'indexed_in' => ['sometimes', 'required', 'array'],
            'indexed_in.*' => ['in:Scopus,SCI,SCIE'],
        ];
    }
}
