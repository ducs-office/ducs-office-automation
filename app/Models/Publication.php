<?php

namespace App\Models;

use App\Casts\CustomTypeArray;
use App\Models\Presentation;
use App\Types\CitationIndex;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    protected $casts = [
        'authors' => 'array',
        'indexed_in' => CustomTypeArray::class . ':' . CitationIndex::class,
        'page_numbers' => 'array',
    ];

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
}
