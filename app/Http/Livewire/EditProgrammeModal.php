<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use App\Models\Programme;
use App\Types\ProgrammeType;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Livewire\Component;

class EditProgrammeModal extends Component
{
    use HasEditModal;

    protected $programme;

    public function mount()
    {
        $this->modalName = Str::kebab(class_basename($this));
        $this->programme = new Programme();

        if (old('programme_id')) {
            $this->setErrorBag(
                session()->get('errors', new ViewErrorBag)->getBag('update')
            );

            $this->show(old('programme_id'));
        }
    }

    public function render()
    {
        return view('livewire.edit-programme-modal', [
            'programme' => $this->programme,
            'types' => ProgrammeType::values(),
        ]);
    }

    public function beforeShow($programmeId)
    {
        $this->programme = Programme::findOrFail($programmeId);
    }
}
