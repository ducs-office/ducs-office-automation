<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\PhdCourse;
use App\Types\PrePhdCourseType;
use Illuminate\Support\Str;
use Livewire\Component;

class EditPhdCourseModal extends Component
{
    use HasEditModal;

    protected $listeners = ['show'];
    public $showModal = false;
    public $modalName;

    protected $course;

    public function mount($errorBag = null)
    {
        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = Str::kebab(class_basename($this));
        if (! $errorBag->isEmpty()) {
            $this->show(old(('course_id')));
        } else {
            $this->course = new PhdCourse();
        }
    }

    public function render()
    {
        return view('livewire.edit-phd-course-modal', [
            'courseTypes' => PrePhdCourseType::values(),
            'course' => $this->course,
        ]);
    }

    public function show($courseId)
    {
        $this->course = PhdCourse::find($courseId);
        $this->showModal = true;
        $this->onShow();
    }
}
