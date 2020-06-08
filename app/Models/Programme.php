<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\ProgrammeType;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $fillable = ['code', 'wef', 'name', 'type', 'duration'];

    protected $dates = ['wef'];

    protected $casts = [
        'wef' => 'date:Y-m-d',
        'type' => CustomType::class . ':' . ProgrammeType::class,
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
        return $this->belongsTo(ProgrammeRevision::class);
    }

    public function scopeWithLatestRevisionId($query)
    {
        return $query->addSelect([
            'latest_revision_id' => ProgrammeRevision::select('id')
                ->whereColumn('programme_id', 'programmes.id')
                ->orderBy('revised_at', 'desc')->limit(1),
        ]);
    }

    public function scopeWithLatestRevision($query)
    {
        return $query->withLatestRevisionId()
            ->with(['latestRevision']);
    }
}
