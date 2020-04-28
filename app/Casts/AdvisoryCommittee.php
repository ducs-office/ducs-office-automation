<?php

namespace App\Casts;

use App\Models\User;
use App\Types\AdvisoryCommitteeMember;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

class AdvisoryCommittee implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        $collection = collect(json_decode($value, true))
            ->map(function ($value) {
                $method = 'get' . Str::studly($value['type']);
                return $this->{$method}($value);
            });

        if ($model->cosupervisor) {
            $collection->prepend($this->getCosupervisor($model->cosupervisor));
        }

        $collection->prepend($this->getSupervisor($model->supervisor));

        return $collection->values()->toArray();
    }

    private function getFacultyTeacher($value)
    {
        return new AdvisoryCommitteeMember('faculty_teacher', [
            'id' => $value['id'],
            'name' => $value['name'],
            'designation' => $value['designation'],
            'affiliation' => $value['affiliation'],
            'email' => $value['email'],
            'phone' => $value['phone'] ?? null,
        ]);
    }

    private function getExternal($value)
    {
        return new AdvisoryCommitteeMember('external', [
            'name' => $value['name'],
            'designation' => $value['designation'],
            'affiliation' => $value['affiliation'],
            'email' => $value['email'],
            'phone' => $value['phone'] ?? null,
        ]);
    }

    private function getCosupervisor($cosupervisor)
    {
        return new AdvisoryCommitteeMember('cosupervisor', [
            'name' => $cosupervisor->name,
            'designation' => $cosupervisor->designation,
            'affiliation' => $cosupervisor->affiliation,
            'email' => $cosupervisor->email,
        ]);
    }

    private function getSupervisor($supervisor)
    {
        return new AdvisoryCommitteeMember('supervisor', [
            'name' => $supervisor->name,
            'designation' => 'Professor',
            'affiliation' => 'Department of Computer Science',
            'email' => $supervisor->email,
        ]);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return collect($value)->filter(function ($item) {
            return in_array($item->type, ['faculty_teacher', 'external']);
        })->map->toArray()
        ->toJson();
    }
}
