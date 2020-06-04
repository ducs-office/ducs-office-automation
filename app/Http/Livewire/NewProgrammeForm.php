<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Types\ProgrammeType;
use Illuminate\Support\Str;
use Livewire\Component;

class NewProgrammeForm extends Component
{
    public $code;
    public $duration;
    public $type;
    public $semester_courses;

    public function mount()
    {
        $this->code = old('code', '');
        $this->duration = old('duration', 3);
        $this->type = old('type', '');
        $this->semester_courses = old('semester_courses', []);
    }

    public function render()
    {
        return view('livewire.new-programme-form', [
            'types' => ProgrammeType::values(),
            'courses' => $this->courses,
        ]);
    }

    public function getCoursesProperty()
    {
        return Course::query()
            ->where('code', 'like', $this->code . '%')
            ->whereNotIn('id', $this->selectedCourses)
            ->get();
    }

    public function getSelectedCoursesProperty()
    {
        return array_reduce(
            $this->semester_courses,
            function ($flatArray, $nestedArray) {
                return [...$flatArray, ...$nestedArray];
            },
            []
        );
    }

    public function updatedCode()
    {
        $this->semester_courses = $this->courses->groupBy(function ($course) {
            $matches = [];
            if (preg_match('~[0-9]+~', $course->code, $matches) <= 0) {
                return null;
            }
            return $matches[0][0];
        })
        ->map(function ($group) { return $group->map->id; })
        ->toArray();
    }
}
