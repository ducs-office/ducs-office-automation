<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgrammeRevision extends Model
{
    protected $fillable = ['revised_at', 'programme_id'];

    protected $dates = ['revised_at'];

    protected $casts = [
        'revised_at' => 'date:Y-m-d',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot(['semester'])
            ->orderBy('semester');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
