<?php

namespace App;

use App\Presentation;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function setAuthorsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['authors'] = implode('|', $value);
        } else {
            $this->attributes['authors'] = $value;
        }
    }

    public function getAuthorsAttribute($value)
    {
        return explode('|', $value);
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

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->orderBy('date', 'desc');
    }
}
