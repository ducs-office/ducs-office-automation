<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Types\CourseType;
use Illuminate\Support\Str;
use Livewire\Component;

class EditCourseModal extends Component
{
    protected $listeners = ['show'];
    public $showModal = false;
    public $modalName;

    protected $course;
    public $courseTypes;

    public function mount($courseTypes, $errorBag = null)
    {
        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = Str::kebab(class_basename($this));
        $this->courseTypes = $courseTypes;

        if (! $errorBag->isEmpty()) {
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

    public function show($courseId)
    {
        $this->course = Course::find($courseId);
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }
}
