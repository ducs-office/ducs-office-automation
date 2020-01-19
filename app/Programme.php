<?php

namespace App;

use App\College;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $guarded = [];

    public function colleges()
    {
        return $this->belongsToMany(College::class, 'colleges_programmes', 'programe_id', 'college_id');
    }

    public function revisions()
    {
        return $this->hasMany(ProgrammeRevision::class);
    }
}
