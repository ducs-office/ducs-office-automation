<?php

namespace App\Http\Livewire;

use App\Models\Remark;
use Illuminate\Support\Str;
use Livewire\Component;

class EditRemarkModal extends Component
{
    protected $listeners = ['show'];
    public $showModal = false;
    public $modalName;

    protected $remark;

    public function mount($errorBag = null)
    {
        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = Str::kebab(class_basename($this));

        if (! $this->getErrorBag()->isEmpty()) {
            $this->show(old('remark_id'));
        } else {
            $this->remark = new Remark();
        }
    }

    public function render()
    {
        return view('livewire.edit-remark-modal', [
            'remark' => $this->remark,
        ]);
    }

    public function show($remarkId)
    {
        $this->remark = Remark::find($remarkId);
        $this->showModal = true;
    }
}
