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
}
