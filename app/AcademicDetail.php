<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicDetail extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];
    
    protected $casts = [
        'venue' => 'array',
        'page_numbers' => 'array',
    ];

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
}
