<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    protected $guareded = [];

    public function recipient() 
    {
        return $this->belongsTo(User::class,'recipient_id');
    }
    
    public function handover()
    {
        return $this->belongsTo(User::class,'handover_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function remarks()
    {
        return $this->morphMany(Remark::class, 'remarkable');
    }
}
