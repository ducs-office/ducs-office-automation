<?php

namespace App;

use App\College;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $guarded = [];

    protected $dates = ['wef'];

    protected $casts = [
        'wef' => 'date:Y-m-d'
    ];

    
    public function colleges()
    {
        return $this->belongsToMany(College::class, 'colleges_programmes', 'programe_id', 'college_id');
    }

    public function revisions()
    {
        return $this->hasMany(ProgrammeRevision::class);
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
                ->orderBy('revised_at', 'desc')->limit(1)
        ])->with(['latestRevision']);
    }
}
