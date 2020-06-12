<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use Illuminate\Support\Str;
use Livewire\Component;

class ApplyForLeaveModal extends Component
{
    use HasEditModal;

    public $scholar;
    public $extensionId;
    public $extensionFromDate;

    public function mount($modalName = null, $errorBag = null, $scholar)
    {
        if ($errorBag != null && $errorBag->hasAny(['to', 'from', 'reason', 'reason_text', 'application'])) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = $modalName ?? Str::kebab(class_basename($this));
        $this->scholar = $scholar;

        if (! $this->getErrorBag()->isEmpty()) {
            $this->show(old('extended_leave_id', 'from'));
        } else {
            $this->extensionId = null;
            $this->extensionFromDate = null;
        }
    }

    public function render()
    {
        return view('livewire.apply-for-leave-modal');
    }

    public function show($data = null)
    {
        $this->extensionId = $data['extensionId'] ?? null;
        $this->extensionFromDate = $data['extensionFromDate'] ?? null;

        $this->showModal = true;
    }
}
