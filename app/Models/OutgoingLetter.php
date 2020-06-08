<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Concerns\Filterable;
use App\Filters\LetterFilters\AfterDate;
use App\Filters\LetterFilters\BeforeDate;
use App\Filters\LetterFilters\ByCreatorId;
use App\Filters\LetterFilters\ByRecipientString;
use App\Filters\LetterFilters\BySenderId;
use App\Filters\LetterFilters\ByType;
use App\Filters\LetterFilters\SearchLike;
use App\Types\OutgoingLetterType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class OutgoingLetter extends Model
{
    use Filterable;

    protected $perPage = 20;

    protected $fillable = [
        'date', 'type', 'subject', 'recipient', 'description', 'amount',
        'sender_id', 'creator_id',
    ];

    protected $filters = [
        BeforeDate::class,
        AfterDate::class,
        ByType::class,
        ByRecipientString::class,
        BySenderId::class,
        ByCreatorId::class,
        SearchLike::class,
    ];

    protected $casts = [
        'date' => 'datetime',
        'type' => CustomType::class . ':' . OutgoingLetterType::class,
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
        $serial_no = 'CS/' . $this->type->serialPrefix() . $this->date->format('Y');
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

    public function remarkStoreUrl()
    {
        return route('staff.outgoing_letters.remarks.store', $this);
    }
}
