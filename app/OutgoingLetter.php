<?php

namespace App;

use App\Model;
use App\Remark;
use Illuminate\Support\Facades\Cache;

class OutgoingLetter extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    protected $allowedFilters = [
        'date' => 'less_than',
        'type' => 'equals',
        'recipient' => 'equals',
        'creator_id' => 'equals',
        'sender_id' => 'equals'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($outgoing_letter) {
            $prefixes = [
                'Bill' => 'TR/',
                'Notesheet' => 'NTS/',
                'General' => ''
            ];

            $year = now()->year;
            $serial_no = "CS/{$prefixes[$outgoing_letter->type]}{$year}";
            $cache_key = "letter_seq_{$serial_no}";
            $number_sequence = str_pad(Cache::increment($cache_key), 4, '0', STR_PAD_LEFT);
            
            $outgoing_letter->serial_no = "$serial_no/$number_sequence";
        });
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function remarks()
    {
        return $this->hasMany(Remark::class, 'letter_id')->orderBy('updated_at', 'DESC');
    }

    public function reminders()
    {
        return $this->hasMany(LetterReminder::class, 'letter_id')->orderBy('created_at', 'DESC');
    }
}
