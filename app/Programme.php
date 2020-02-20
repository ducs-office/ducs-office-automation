<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $fillable = ['code', 'wef', 'name', 'type', 'duration'];

    protected $dates = ['wef'];

    protected $casts = [
        'wef' => 'date:Y-m-d',
    ];

    public function colleges()
    {
        return $this->belongsToMany(College::class, 'colleges_programmes', 'programe_id', 'college_id');
    }

    public function revisions()
    {
        return $this->hasMany(ProgrammeRevision::class)
            ->orderBy('revised_at', 'desc');
    }

    public function latestRevision()
    {
        return $this->hasOne(ProgrammeRevision::class);
    }

    public function scopeWithLatestRevision($query)
    {
        return $query->addSelect([
            'latest_revision_id' => ProgrammeRevision::select('id')
                ->whereColumn('programme_id', 'programmes.id')
                ->orderBy('revised_at', 'desc')->limit(1),
        ])->with(['latestRevision']);
    }
}
