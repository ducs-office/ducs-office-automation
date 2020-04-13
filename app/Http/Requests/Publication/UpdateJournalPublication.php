<?php

namespace App\Http\Requests\Publication;

use App\Types\CitationIndex;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJournalPublication extends FormRequest
{
    public function rules()
    {
        $months = array_map(function ($m) {
            return Carbon::createFromFormat('m', $m)->format('F');
        }, range(1, 12));

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
            'date.month' => ['sometimes', 'required', Rule::in($months)],
            'date.year' => ['sometimes', 'required', 'numeric'],
            'number' => ['nullable', 'numeric'],
            'indexed_in' => ['sometimes', 'required', 'array'],
            'indexed_in.*' => [Rule::in(CitationIndex::values())],
        ];
    }
}
