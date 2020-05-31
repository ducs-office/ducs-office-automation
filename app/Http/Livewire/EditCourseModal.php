<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\Course;
use App\Types\CourseType;
use Illuminate\Support\Str;
use Livewire\Component;

class EditCourseModal extends Component
{
    use HasEditModal;

    protected $course;

    public function mount($errorBag)
    {
        if (! $this->getErrorBag()->isEmpty()) {
            $this->show(old('course_id'));
        } else {
            $this->course = new Course();
        }
    }

    public function render()
    {
        return view('livewire.edit-course-modal', [
            'courseTypes' => CourseType::values(),
            'course' => $this->course,
        ]);
    }

    public function beforeShow($data)
    {
        $this->course = Course::find($data['course_id']);
    }
}
