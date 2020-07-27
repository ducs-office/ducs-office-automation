<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\Scholar;
use Illuminate\Support\Str;
use Livewire\Component;

class EditScholarModal extends Component
{
    use HasEditModal;

    protected $scholar;
    public $supervisors;
    public $cosupervisors;

    public function mount($supervisors, $cosupervisors, $errorBag = null)
    {
        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->supervisors = $supervisors->toArray();
        $this->cosupervisors = $cosupervisors->toArray();
        $this->scholar = new Scholar();

        $this->modalName = Str::kebab(class_basename($this));

        if (! $this->getErrorBag()->isEmpty()) {
            $this->show(old('scholar_id'));
        } else {
            $this->scholar = new Scholar();
        }
    }

    public function render()
    {
        return view('livewire.edit-scholar-modal', [
            'scholar' => $this->scholar,
            'supervisors' => $this->supervisors,
            'cosupervisors' => $this->cosupervisors,
        ]);
    }

    public function beforeShow($scholarId)
    {
        $this->scholar = Scholar::find($scholarId);
    }
}
