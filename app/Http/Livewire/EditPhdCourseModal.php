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

    protected $course;

    public function mount($errorBag)
    {
        if (! $this->getErrorBag()->isEmpty()) {
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

    public function beforeShow($data)
    {
        $this->course = PhdCourse::find($data['course_id']);
    }
}
