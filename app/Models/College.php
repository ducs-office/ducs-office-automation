<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = [
        'code', 'name', 'address', 'principal_name',
        'principal_phones', 'principal_emails', 'website',
    ];

    protected $casts = [
        'principal_emails' => 'array',
        'principal_phones' => 'array',
    ];

    public function programmes()
    {
        return $this->belongsToMany(
            Programme::class,
            'colleges_programmes',
            'college_id',
            'programme_id'
        );
    }
}
