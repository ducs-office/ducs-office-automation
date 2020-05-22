<?php

namespace App\Concerns;

use App\Models\Publication;
use App\Types\CitationIndex;
use Illuminate\Support\Arr;

trait HasPublications
{
    public function publications()
    {
        return $this->morphMany(Publication::class, 'author');
    }

    public function journals()
    {
        return $this->publications()->journal();
    }

    public function conferences()
    {
        return $this->publications()->conference();
    }

    public function countSCIOrSCIEJournals()
    {
        return $this->journals()->pluck('indexed_in')->filter(function ($value) {
            $indexIn = collect($value);
            return $indexIn->contains(CitationIndex::SCI) || $indexIn->contains(CitationIndex::SCIE);
        })->count();
    }

    public function countMRPublications()
    {
        return $this->publications()->pluck('indexed_in')->filter(function ($value) {
            return collect($value)->contains(CitationIndex::MR);
        })->count();
    }

    public function CountScopusNotSCIOrSCIEPublications()
    {
        return $this->publications->pluck('indexed_in')->filter(function ($value) {
            $indexIn = collect($value);
            return $indexIn->contains(CitationIndex::SCOPUS)
                && ! ($indexIn->Contains(CitationIndex::SCI))
                && ! ($indexIn->Contains(CitationIndex::SCIE));
        })->count();
    }
}
