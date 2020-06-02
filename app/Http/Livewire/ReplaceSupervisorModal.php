<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\Scholar;
use Illuminate\Support\Str;
use Livewire\Component;

class ReplaceSupervisorModal extends Component
{
    use HasEditModal;

    protected $scholar;
    public $supervisors;

    public function mount($supervisors)
    {
        $this->supervisors = $supervisors->toArray();
        $this->scholar = new Scholar();

        $this->modalName = Str::kebab(class_basename($this));
    }

    public function render()
    {
        return view('livewire.replace-supervisor-modal', [
            'scholar' => $this->scholar,
            'supervisors' => $this->supervisors,
        ]);
    }

    public function beforeShow($scholarId)
    {
        $this->scholar = Scholar::find($scholarId);
    }
}
