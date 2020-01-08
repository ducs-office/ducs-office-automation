<?php

namespace App;
use App\Programme;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class College extends Model
{
    
    protected $guarded=[];

    public function programmes() 
    {
        return $this->belongsToMany(Programme::class,'colleges_programmes', 'college_id', 'programme_id');
    }
}
