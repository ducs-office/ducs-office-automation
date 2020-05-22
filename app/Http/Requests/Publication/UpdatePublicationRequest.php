<?php

namespace App\Http\Requests\Publication;

use App\Types\CitationIndex;
use App\Types\PublicationType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePublicationRequest extends FormRequest
{
    /**
     * Prepare data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'date' => $this['date']['month'] . ' ' . $this['date']['year'],
            'is_published' => $this->filled('is_published') ?? false,
        ]);
    }

    public function rules()
    {
        $rules = [
            'type' => ['required', Rule::in(PublicationType::values())],
            'paper_title' => ['sometimes', 'required', 'string', 'max:400'],
            'document' => ['sometimes', 'required'],

            'is_published' => ['sometimes', 'required', Rule::in([true, false])],

            'co_authors' => ['nullable', 'array', 'max:10', 'min:1'],
            'co_authors.*.name' => ['required', 'string'],
            'co_authors.*.noc' => ['required', 'file', 'max:200', 'mimeTypes:application/pdf, image/*'],

            'date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'volume' => ['nullable', 'integer'],
            'publisher' => ['sometimes', 'required', 'string', 'max:100'],
            'page_numbers' => ['sometimes', 'required', 'array', 'size:2'],
            'page_numbers.0' => ['sometimes', 'required', 'integer'],
            'page_numbers.1' => ['sometimes', 'required', 'integer', 'gte:page_numbers.0'],
            'number' => ['nullable', 'numeric'],
            'indexed_in' => ['sometimes', 'required', 'array'],
            'indexed_in.*' => [Rule::in(CitationIndex::values())],
            'paper_link' => ['nullable', 'url'],
            'city' => ['sometimes', 'required_if:type,' . PublicationType::CONFERENCE, 'nullable', 'string'],
            'country' => ['sometimes', 'required_if:type,' . PublicationType::CONFERENCE, 'nullable', 'string'],
            'publisher' => ['sometimes', 'required_if:type,' . PublicationType::JOURNAL, 'nullable', 'string', 'max:100'],
            'number' => ['nullable', 'numeric'],
        ];

        return $rules;
    }

    public function coAuthorsDetails()
    {
        return array_map(static function ($coAuthor) {
            return [
                'name' => $coAuthor['name'],
                'noc_path' => $coAuthor['noc']->store('/publications/co_authors_noc'),
            ];
        }, $this['co_authors'] ?? []);
    }
}
