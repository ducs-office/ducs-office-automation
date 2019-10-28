<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LetterReminder extends Model
{
    protected $fillable = ['letter_id'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($letter_reminder) {
            $prefixes = [
                'Bill' => 'TR/',
                'Notesheet' => 'NTS/',
                'General' => ''
            ];

            $year = now()->year;
            $serial_no = "CS/{$prefixes[$letter_reminder->letter->type]}RM/{$year}";
            $cache_key = "letter_seq_{$serial_no}";
            $number_sequence = str_pad(Cache::increment($cache_key), 4, '0', STR_PAD_LEFT);
            
            $letter_reminder->serial_no = "$serial_no/$number_sequence";

            return $letter_reminder;
        });
    }
    
    public function letter()
    {
        return $this->belongsTo(OutgoingLetter::class, 'letter_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
