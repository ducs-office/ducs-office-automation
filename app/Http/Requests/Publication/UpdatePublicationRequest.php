<?php

namespace App\Http\Requests\Publication;

use App\Types\CitationIndex;
use App\Types\PublicationType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
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
        if ($this->date && isset($this->date['month']) && isset($this->date['year'])) {
            $this->merge(['date' => $this->date['month'] . ' ' . $this->date['year']]);
        }

        $this->merge([
            'is_published' => $this->filled('is_published') ?? false,
        ]);
    }

    public function rules()
    {
        $rules = [
            'type' => ['required', Rule::in(PublicationType::values())],
            'paper_title' => ['sometimes', 'required', 'string', 'max:400'],
            'document' => ['sometimes', 'required'],

            'is_published' => ['required', 'boolean'],
            'co_authors' => ['nullable', 'array', 'max:10', 'min:1'],
            'co_authors.*.name' => ['required', 'string'],
            'co_authors.*.noc' => ['required', 'file', 'max:200', 'mimeTypes:application/pdf, image/*'],

            'name' => ['sometimes', 'exclude_if:is_published,false', 'string', 'max:100'],
            'date' => ['sometimes', 'exclude_if:is_published,false', 'date', 'before_or_equal:today'],
            'volume' => ['exclude_if:is_published,false', 'nullable', 'integer'],
            'publisher' => ['sometimes', 'exclude_if:is_published,false', 'string', 'max:100'],
            'page_numbers' => ['sometimes', 'exclude_if:is_published,false', 'array', 'size:2'],
            'page_numbers.0' => ['sometimes', 'exclude_if:is_published,false', 'integer'],
            'page_numbers.1' => ['sometimes', 'exclude_if:is_published,false', 'integer', 'gte:page_numbers.0'],
            'number' => ['exclude_if:is_published,false', 'nullable', 'numeric'],
            'indexed_in' => ['sometimes', 'exclude_if:is_published,false', 'array'],
            'indexed_in.*' => ['sometimes', 'exclude_if:is_published,false', Rule::in(CitationIndex::values())],
            'paper_link' => ['exclude_if:is_published,false', 'nullable', 'url'],
            'city' => ['sometimes', 'exclude_if:is_published,false', 'exclude_if:type,' . PublicationType::JOURNAL, 'string'],
            'country' => ['sometimes', 'exclude_if:is_published,false', 'exclude_if:type,' . PublicationType::JOURNAL, 'string'],
            'publisher' => ['sometimes', 'exclude_if:is_published,false', 'exclude_if:type,' . PublicationType::CONFERENCE, 'string', 'max:100'],
            'number' => ['exclude_if:is_published,false', 'nullable', 'numeric'],
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
        }, $this->co_authors ?? []);
    }

    public function updateDocumentConditionally()
    {
        if ($this->document) {
            Storage::delete($this->route('publication')->document_path);
            return ['document_path' => $this->file('document')->store('publications')];
        }

        return [];
    }
}
