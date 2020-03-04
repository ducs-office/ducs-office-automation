<?php

namespace App;

use App\Advisor;
use Illuminate\Database\Eloquent\Model;

class ScholarProfile extends Model
{
    protected $table = 'scholars_profile';

    protected $primaryKey = 'scholar_id';

    protected $fillable = [
        'phone_no',
        'address',
        'category',
        'admission_via',
        'supervisor_type',
        'supervisor_id',
    ];

    public function profilePicture()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function supervisor()
    {
        return $this->morphTo('supervisor');
    }

    public function advisors()
    {
        return $this->hasMany(Advisor::class, 'scholar_id');
    }

    public function advisoryCommittee()
    {
        return $this->advisors()->where('type', 'A');
    }

    public function coSupervisors()
    {
        return $this->advisors()->where('type', 'C');
    }
}
