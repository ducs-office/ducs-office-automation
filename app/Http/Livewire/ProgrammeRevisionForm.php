<?php

namespace App\Http\Livewire;

use App\Models\Course;
use Illuminate\Support\ViewErrorBag;
use Livewire\Component;

class ProgrammeRevisionForm extends Component
{
    public $programme;
    public $revised_at;
    public $semester_courses;

    public function mount($programme, $semesterCourses = [], $revisedAt = null)
    {
        $this->programme = $programme;
        $this->semester_courses = old('semester_courses', $semesterCourses);
        foreach (range(1, $programme->duration * 2) as $semester) {
            if (! array_key_exists($semester, $this->semester_courses)) {
                $this->semester_courses[$semester] = [];
            }
        }
        $this->revised_at = old('revised_at', optional($revisedAt)->format('Y-m-d') ?? '');

        $this->setErrorBag(session()->get('errors', new ViewErrorBag)->default);
    }

    public function render()
    {
        return view('livewire.programme-revision-form', [
            'courses' => $this->courses,
        ]);
    }

    protected function getCoursesProperty()
    {
        return Course::query()
            ->where('code', 'like', $this->programme->code . '%')
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

    public function disabledIndices($semester)
    {
        return $this->courses->filter(function ($course) use ($semester) {
            return $this->isSelectedExcludingSemester($course->id, $semester);
        })->keys()->all();
    }

    public function isSelectedExcludingSemester($courseId, $currentSemester)
    {
        foreach ($this->semester_courses as $semester => $courses) {
            if ($currentSemester != $semester && in_array($courseId, $courses)) {
                return true;
            }
        }

        return false;
    }
}
