<?php

namespace App\Http\Livewire;

use App\Models\Publication;
use Illuminate\Support\Str;
use Livewire\Component;

class CoAuthorsModal extends Component
{
    protected $listeners = ['show'];
    protected $publication;

    public $modalName;
    public $showModal = false;

    public function mount($modalName = null, $errorBag = null)
    {
        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = $modalName ?? Str::kebab(class_basename($this));
        $this->publication = new Publication();

        if (!$this->getErrorBag()->isEmpty()) {
            $this->show(old('publication_id'));
        }
    }

    public function render()
    {
        return view('livewire.co-authors-modal', [
            'publication' => $this->publication,
        ]);
    }

    public function show($publicationId)
    {
        $this->publication = Publication::find($publicationId);
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }
}
