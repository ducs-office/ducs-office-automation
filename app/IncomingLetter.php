<?php

namespace App;


use Illuminate\Support\Facades\Cache;
use App\Model;
use App\Remark;

class IncomingLetter extends Model
{
    protected $fillable = [
        'date', 'received_id', 'sender', 'description', 'subject', 'priority',
        'recipient_id', 'handover_id', 
    ];

    protected $dates = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (IncomingLetter $incoming_letter) {
            $year = $incoming_letter->date->format('y');
            $seq_id = "CS/D/{$year}";
            $cache_key = "letter_seq_{$seq_id}";
            $number_seq = str_pad(Cache::increment($cache_key) , 4, STR_PAD_LEFT);
            
            $incoming_letter->serial_no = "$seq_id/$number_seq";

            return $incoming_letter;
        });
    }
    protected $allowedFilters = [
        'date' => 'less_than',
        'priority' => 'equals',
        'recipient_id' => 'equals',
        'sender' => 'equals',
        'handover_id' => 'equals'
    ];

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
