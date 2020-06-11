<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\Leave;
use App\Types\LeaveStatus;
use Illuminate\Support\Str;
use Livewire\Component;

class RespondToLeaveModal extends Component
{
    use HasEditModal;

    protected $leave;
    public $scholar;

    public function mount($modalName = null, $errorBag = null, $scholar)
    {
        if ($errorBag != null && $errorBag->hasAny(['response', 'response_letter'])) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = $modalName ?? Str::kebab(class_basename($this));
        $this->scholar = $scholar;

        if (! $this->getErrorBag()->isEmpty()) {
            $this->show(old('leave_id'));
        } else {
            $this->leave = new Leave();
        }
    }

    public function render()
    {
        return view('livewire.respond-to-leave-modal', [
            'leave' => $this->leave,
        ]);
    }

    public function beforeShow($data)
    {
        $this->leave = Leave::find($data);
    }
}
