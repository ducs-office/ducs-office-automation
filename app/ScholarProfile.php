<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScholarProfile extends Model
{
    protected $table = 'scholars_profile';

    protected $primaryKey = 'scholar_id';

    protected $fillable = [
        'phone_no',
        'address',
        'category',
        'admission_via'
    ];

    public function profilePicture()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }
}
