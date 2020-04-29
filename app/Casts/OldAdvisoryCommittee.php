<?php

namespace App\Casts;

use App\Models\User;
use App\Types\AdvisoryCommitteeMember;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;

class OldAdvisoryCommittee implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return array_map(function ($committee) {
            $committee = collect($committee);
            $fromDate = $committee->pull('from_date');
            $toDate = $committee->pull('to_date');
            $committee = $committee->map(function ($value) {
                return $this->getFromArray($value);
            })->values()->toArray();

            return [
                'committee' => $committee,
                'from_date' => Carbon::parse($fromDate),
                'to_date' => Carbon::parse($toDate),
            ];
        }, json_decode($value, true));
    }

    private function getFromArray($value)
    {
        return new AdvisoryCommitteeMember($value['type'], [
            'id' => $value['id'] ?? null,
            'name' => $value['name'],
            'designation' => $value['designation'],
            'affiliation' => $value['affiliation'],
            'email' => $value['email'],
            'phone' => $value['phone'] ?? null,
        ]);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return collect($value)->map(function ($oldCommittee) {
            return collect($oldCommittee['committee'])
                ->map->toArray()
                ->put('from_date', $oldCommittee['from_date']->format('d F Y'))
                ->put('to_date', $oldCommittee['to_date']->format('d F Y'))
                ->toArray();
        })
        ->toJson();
    }
}
