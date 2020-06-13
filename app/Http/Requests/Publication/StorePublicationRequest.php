<?php

namespace App\Http\Requests\Publication;

use App\Models\Scholar;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePublicationRequest extends FormRequest
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
            'is_published' => $this->has('is_published'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => ['required', Rule::in(PublicationType::values())],
            'paper_title' => ['required', 'string', 'max:400'],
            'is_published' => ['required', 'boolean'],

            'co_authors' => ['nullable', 'array', 'max:10'],
            'co_authors.is_supervisor' => ['nullable'],
            'co_authors.is_cosupervisor' => ['nullable'],
            'co_authors.others' => ['nullable', 'array'],
            'co_authors.others.*.name' => ['required', 'string'],
            'co_authors.others.*.noc' => ['nullable', 'file', 'max:200', 'mimeTypes:application/pdf, image/*'],

            'name' => ['exclude_if:is_published,false', 'required', 'string', 'max:400'],
            'date' => ['exclude_if:is_published,false', 'required', 'date', 'before_or_equal:today'],
            'volume' => ['exclude_if:is_published,false', 'nullable', 'integer'],
            'indexed_in' => ['exclude_if:is_published,false', 'required', 'array'],
            'indexed_in.*' => [Rule::in(CitationIndex::values())],
            'page_numbers' => ['exclude_if:is_published,false', 'required', 'array', 'size:2'],
            'page_numbers.0' => ['exclude_if:is_published,false', 'required', 'integer'],
            'page_numbers.1' => ['exclude_if:is_published,false', 'required', 'integer', 'gte:page_numbers.0'],
            'paper_link' => ['exclude_if:is_published,false', 'nullable', 'url'],
            'city' => ['exclude_if:is_published,false', 'required_if:type,' . PublicationType::CONFERENCE, 'nullable', 'string'],
            'country' => ['exclude_if:is_published,false', 'required_if:type,' . PublicationType::CONFERENCE, 'nullable', 'string'],
            'publisher' => ['exclude_if:is_published,false', 'required_if:type,' . PublicationType::JOURNAL, 'nullable', 'string', 'max:100'],
            'number' => ['exclude_if:is_published,false', 'nullable', 'numeric'],
        ];

        if (get_class($this->user()) === Scholar::class) {
            $rules['document'] = ['required', 'file', 'mimetypes:application/pdf,image/*', 'max:200'];
        }

        return $rules;
    }

    public function coAuthorsDetails()
    {
        $coAuthors = array_map(static function ($coAuthor) {
            return [
                'name' => $coAuthor['name'],
                'noc_path' => ($coAuthor['noc']) ? $coAuthor['noc']->store('/publications/co_authors_noc') : '',
                'type' => 0,
            ];
        }, $this->co_authors['others'] ?? []);

        if ($this->filled('co_authors.is_supervisor')) {
            $coAuthors[] = [
                'user_id' => $this->user()->currentSupervisor->id,
                'type' => 1,
            ];
        }

        if ($this->filled('co_authors.is_cosupervisor')) {
            $coAuthors[] = [
                'user_id' => $this->user()->currentCosupervisor->id,
                'type' => 2,
            ];
        }

        return $coAuthors;
    }

    public function storeDocument()
    {
        return $this->file('document')->store('publications');
    }
}
