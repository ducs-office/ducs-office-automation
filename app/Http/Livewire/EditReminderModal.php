<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\LetterReminder;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class EditReminderModal extends Component
{
    protected $listeners = ['show'];
    public $showModal = false;
    public $modalName;

    protected $reminder;

    public function mount($errorBag = null)
    {
        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = Str::kebab(class_basename($this));

        if (! $this->getErrorBag()->isEmpty()) {
            $this->show(old('reminder_id'));
        } else {
            $this->reminder = new LetterReminder();
        }
    }

    public function render()
    {
        return view('livewire.edit-reminder-modal', [
            'reminder' => $this->reminder,
        ]);
    }

    public function show($reminderId)
    {
        $this->reminder = LetterReminder::find($reminderId);
        $this->showModal = true;
    }
}
