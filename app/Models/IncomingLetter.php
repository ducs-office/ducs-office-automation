<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Concerns\Filterable;
use App\Filters\LetterFilters\AfterDate;
use App\Filters\LetterFilters\BeforeDate;
use App\Filters\LetterFilters\ByPriority;
use App\Filters\LetterFilters\ByRecipientId;
use App\Filters\LetterFilters\BySenderString;
use App\Filters\LetterFilters\SearchLike;
use App\Types\Priority;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class IncomingLetter extends Model
{
    use Filterable;

    protected $fillable = [
        'date', 'received_id', 'sender', 'description', 'subject', 'priority',
        'recipient_id', 'creator_id',
    ];

    protected $casts = [
        'date' => 'datetime',
        'priority' => CustomType::class . ':' . Priority::class,
    ];

    protected $filters = [
        BeforeDate::class,
        AfterDate::class,
        BySenderString::class,
        ByRecipientId::class,
        ByPriority::class,
        SearchLike::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(static function (IncomingLetter $incoming_letter) {
            $incoming_letter->assignSerialNumber();

            if (! $incoming_letter->creator_id) {
                $incoming_letter->creator_id = Auth::id();
            }
        });

        static::updating(static function (IncomingLetter $incoming_letter) {
            $incoming_letter->assignSerialNumber();
        });
    }

    public function assignSerialNumber()
    {
        $serial = 'CS/D/' . $this->date->format('Y');
        $number_sequence = Cache::increment("letter_seq_{$serial}");

        return $this->serial_no = $serial . '/' . str_pad($number_sequence, 4, '0', STR_PAD_LEFT);
    }

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

    public function remarkStoreUrl()
    {
        return route('staff.incoming_letters.remarks.store', $this);
    }
}
