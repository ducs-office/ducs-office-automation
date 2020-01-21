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
}
