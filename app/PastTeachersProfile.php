<?php

namespace App;

use App\PastTeachingDetail;
use Illuminate\Database\Eloquent\Model;

class PastTeachersProfile extends Model
{
    protected $guarded = [''];

    public function past_teaching_details()
    {
        return $this->hasMany(PastTeachingDetail::class, 'past_teachers_profile_id');
    }
}
