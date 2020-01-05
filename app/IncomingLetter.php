<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use App\Model;
use App\Remark;
use Auth;

class IncomingLetter extends Model
{
    protected $fillable = [
        'date', 'received_id', 'sender', 'description', 'subject', 'priority',
        'recipient_id', 'creator_id'
    ];

    protected $dates = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (IncomingLetter $incoming_letter) {
            $year = $incoming_letter->date->format('Y');
            $seq_id = "CS/D/{$year}";
            $cache_key = "letter_seq_{$seq_id}";
            $number_seq = str_pad(Cache::increment($cache_key), 4, "0", STR_PAD_LEFT);
            $incoming_letter->serial_no = "$seq_id/$number_seq";
            
            if (!isset($incoming_letter->creator_id)) {
                $incoming_letter->creator_id = Auth::user()->id;
            }

            return $incoming_letter;
        });

        static::updating(function (IncomingLetter $incoming_letter) {
            $year = $incoming_letter->date->format('Y');
            $seq_id = "CS/D/{$year}";
            $cache_key = "letter_seq_{$seq_id}";
            $number_seq = str_pad(Cache::increment($cache_key), 4, "0", STR_PAD_LEFT);
            $incoming_letter->serial_no = "$seq_id/$number_seq";

            return $incoming_letter;
        });
    }
    protected $allowedFilters = [
        'date' => 'less_than',
        'priority' => 'equals',
        'recipient_id' => 'equals',
        'sender' => 'equals',
    ];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function handovers()
    {
        return $this->belongsToMany(User::class, 'handovers', 'letter_id', 'user_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function remarks()
    {
        return $this->morphMany(Remark::class, 'remarkable')->orderBy('updated_at', 'DESC');
    }
}
