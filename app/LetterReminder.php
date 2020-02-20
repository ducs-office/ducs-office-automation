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

        static::creating(static function ($letter_reminder) {
            $prefixes = [
                'Bill' => 'TR/',
                'Notesheet' => 'NTS/',
                'General' => '',
            ];

            $serial = 'CS/' . $prefixes[$letter_reminder->letter->type] . 'RM/' . now()->year;
            $number_sequence = Cache::increment("letter_seq_{$serial}");
            $serial_no = $serial . '/' . str_pad($number_sequence, 4, '0', STR_PAD_LEFT);

            $letter_reminder->serial_no = $serial_no;
        });

        static::deleting(static function ($letter_reminder) {
            $letter_reminder->attachments->each->delete();
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
