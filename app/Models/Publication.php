<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Casts\CustomTypeArray;
use App\Exceptions\InvalidTypeValue;
use App\Models\Presentation;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Publication extends Model
{
    protected $fillable = [
        'type',
        'name',
        'paper_title',
        'date',
        'volume',
        'publisher',
        'number',
        'indexed_in',
        'page_numbers',
        'city',
        'country',
        'author_type',
        'author_id',
        'is_published',
        'document_path',
        'paper_link',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(static function ($publication) {
            Storage::delete($publication->document_path);
        });
    }

    protected $dates = ['date'];

    protected $casts = [
        'indexed_in' => CustomTypeArray::class . ':' . CitationIndex::class,
        'page_numbers' => 'array',
        'is_published' => 'boolean',
    ];

    public function scopeJournal(Builder $builder)
    {
        return $builder->whereType(PublicationType::JOURNAL)->orderBy('date', 'DESC');
    }

    public function scopeConference(Builder $builder)
    {
        return $builder->whereType(PublicationType::CONFERENCE)->orderBy('date', 'DESC');
    }

    public function author()
    {
        return $this->morphTo('author');
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->orderBy('date', 'desc');
    }

    public function coAuthors()
    {
        return $this->hasMany(CoAuthor::class, 'publication_id');
    }

    public function isPublished()
    {
        return $this->is_published === true;
    }

    public function isJournal()
    {
        return $this->type === PublicationType::JOURNAL;
    }

    public function isConference()
    {
        return $this->type === PublicationType::CONFERENCE;
    }

    public function getUrl()
    {
        if ($this->author_type === Scholar::class) {
            return route('scholars.publications.show', [$this->author, $this]);
        }

        return route('users.publications.show', [$this->author, $this]);
    }

    public function __toString()
    {
        $authorName = $this->author->name;
        $allAuthorNames = $this->coAuthors->pluck('name')->prepend($authorName)->implode(', ');
        $publicationString = "{$allAuthorNames}. ";
        if (! $this->isPublished()) {
            $publicationString = '[Un-published] ' . $publicationString;
        }

        if ($this->date) {
            $monthYear = $this->date->format('F Y');
            $publicationString .= "{$monthYear}. ";
        }

        $paperTitle = $this->paper_link
            ? "<a class=\"link\" href=\"{$this->paper_link}\">\"{$this->paper_title}\"</a>"
            : "\"{$this->paper_title}\"";

        $publicationString .= "{$paperTitle}. ";

        if ($this->isPublished()) {
            $journalOrConferenceName = implode(' ', [$this->publisher, $this->name]);
            if ($this->volume) {
                $journalOrConferenceName .= $this->type === PublicationType::JOURNAL
                    ? ", Volume {$this->volume}"
                    : ", Edition {$this->volume}";
            }
            if ($this->number) {
                $journalOrConferenceName .= ", Number {$this->number}";
            }
            $journalOrConferenceName .= ', pages: ' . implode('-', $this->page_numbers);

            $publicationString .= "In {$journalOrConferenceName}";
        }

        return strip_tags($publicationString, '<a>');
    }
}
