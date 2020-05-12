<?php

namespace App\Models;

use App\Casts\CustomTypeArray;
use App\Models\Presentation;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'type',
        'name',
        'paper_title',
        'date',
        'volume',
        'publisher',
        'number',
        'indexed_in',
        'page_numbers',
        'city',
        'country',
        'main_author_type',
        'main_author_id',
    ];

    protected $dates = ['date'];

    protected $casts = [
        'indexed_in' => CustomTypeArray::class . ':' . CitationIndex::class,
        'page_numbers' => 'array',
    ];

    public function scopeJournal(Builder $builder)
    {
        return $builder->whereType(PublicationType::JOURNAL)->orderBy('date', 'DESC');
    }

    public function scopeConference(Builder $builder)
    {
        return $builder->whereType(PublicationType::CONFERENCE)->orderBy('date', 'DESC');
    }

    public function setIndexedInAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['indexed_in'] = implode('|', $value);
        } else {
            $this->attributes['indexed_in'] = $value;
        }
    }

    public function getIndexedInAttribute($value)
    {
        return explode('|', $value);
    }

    public function setPageNumbersAttribute($value)
    {
        $this->attributes['page_numbers'] = implode('-', $value);
    }

    public function getPageNumbersAttribute($value)
    {
        return explode('-', $value);
    }

    public function mainAuthor()
    {
        return $this->morphTo('main_author');
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->orderBy('date', 'desc');
    }

    public function coAuthors()
    {
        return $this->hasMany(CoAuthor::class, 'publication_id');
    }
}
