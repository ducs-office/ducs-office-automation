<?php

namespace App\Http\Requests\Publication;

use App\Types\CitationIndex;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJournalPublication extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $months = array_map(function ($m) {
            return Carbon::createFromFormat('m', $m)->format('F');
        }, range(1, 12));

        return [
            'name' => ['required', 'string', 'max:400'],
            'authors' => ['required', 'array', 'max:10', 'min:1'],
            'authors.0' => ['required', 'string'],
            'paper_title' => ['required', 'string', 'max:400'],
            'date' => ['required', 'array', 'size:2'],
            'date.month' => ['required', Rule::in($months)],
            'date.year' => ['required', 'numeric'],
            'volume' => ['nullable', 'integer'],
            'publisher' => ['required', 'string', 'max:100'],
            'number' => ['nullable', 'numeric'],
            'indexed_in' => ['required', 'array'],
            'indexed_in.*' => [Rule::in(CitationIndex::values())],
            'page_numbers' => ['required', 'array', 'size:2'],
            'page_numbers.0' => ['required', 'integer'],
            'page_numbers.1' => ['required', 'integer', 'gte:page_numbers.0'],
        ];
    }
}
