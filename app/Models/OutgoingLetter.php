<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class OutgoingLetter extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'date', 'type', 'subject', 'recipient', 'description', 'amount',
        'sender_id', 'creator_id',
    ];

    protected $dates = ['date'];

    protected $allowedFilters = [
        'date' => 'less_than',
        'type' => 'equals',
        'recipient' => 'equals',
        'creator_id' => 'equals',
        'sender_id' => 'equals',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(static function ($letter) {
            $letter->assignSerialNumber();
        });

        static::updating(static function ($letter) {
            $letter->assignSerialNumber();
        });
    }

    public function assignSerialNumber()
    {
        $prefixes = [
            'Bill' => 'TR/',
            'Notesheet' => 'NTS/',
            'General' => '',
        ];

        $serial_no = 'CS/' . $prefixes[$this->type] . $this->date->format('Y');
        $number_sequence = Cache::increment("letter_seq_{$serial_no}");

        $this->serial_no = $serial_no . '/' . str_pad($number_sequence, 4, '0', STR_PAD_LEFT);

        return $this->serial_no;
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function remarks()
    {
        return $this->morphMany(Remark::class, 'remarkable')->orderBy('updated_at', 'DESC');
    }

    public function reminders()
    {
        return $this->hasMany(LetterReminder::class, 'letter_id')->orderBy('created_at', 'DESC');
    }
}
