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
        $this->semester_courses = old('semester_courses', [
            '1' => [], '2' => [], '3' => [],
            '4' => [], '5' => [], '6' => [],
        ]);
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
            ->orWhereIn('id', $this->selectedCourses)
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

    public function updateDuration()
    {
        foreach (range(1, $this->duration * 2) as $semester) {
            if (! array_key_exists($semester, $this->semester_courses)) {
                $this->semester_courses["{$semester}"] = [];
            }
        }
    }

    public function updatedCode()
    {
        $this->courses->each(function ($course) {
            $matches = [];

            if (preg_match('~([0-9])[0-9]*$~', $course->code, $matches) <= 0) {
                return null;
            }

            array_push($this->semester_courses[$matches[1]], $course->id);
        });
    }
}
