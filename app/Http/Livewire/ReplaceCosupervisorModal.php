<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\Scholar;
use Illuminate\Support\Str;
use Livewire\Component;

class ReplaceCosupervisorModal extends Component
{
    use HasEditModal;

    protected $scholar;
    public $cosupervisors;

    public function mount($cosupervisors)
    {
        $this->cosupervisors = $cosupervisors->toArray();
        $this->scholar = new Scholar();

        $this->modalName = Str::kebab(class_basename($this));
    }

    public function render()
    {
        return view('livewire.replace-cosupervisor-modal', [
            'scholar' => $this->scholar,
            'supervisors' => $this->cosupervisors,
        ]);
    }

    public function beforeShow($scholarId)
    {
        $this->scholar = Scholar::find($scholarId);
    }
}
