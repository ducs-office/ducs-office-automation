<?php

namespace App;

use App\Course;

use Illuminate\Database\Eloquent\Model;

class ProgrammeRevision extends Model
{
    protected $fillable = ['revised_at'];

    protected $dates = ['revised_at'];

    protected $casts = [
        'revised_at' => 'date:Y-m-d'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot(['semester']);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}