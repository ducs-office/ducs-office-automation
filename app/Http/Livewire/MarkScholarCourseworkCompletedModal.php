<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\PhdCourse;
use Illuminate\Support\Str;
use Livewire\Component;

class MarkScholarCourseworkCompletedModal extends Component
{
    use HasEditModal;

    protected $course;
    public $scholar;

    public function mount($scholar, $errorBag = null)
    {
        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = Str::kebab(class_basename($this));
        $this->scholar = $scholar;
        $this->course = new PhdCourse();

        if (! $this->getErrorBag()->isEmpty()) {
            $this->show(old(('course_id')));
        }
    }

    public function render()
    {
        return view('livewire.mark-scholar-coursework-completed-modal', [
            'course' => $this->course,
        ]);
    }

    public function beforeShow($courseId)
    {
        $this->course = PhdCourse::find($courseId);
    }
}
