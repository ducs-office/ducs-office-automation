<?php

namespace App\Http\Livewire;

use App\Models\Programme;
use App\Models\User;
use Livewire\Component;

class TypeaheadProgrammesLatestRevision extends Component
{
    public $open;
    public $input_id;
    public $name;
    public $multiple;
    public $limit;
    public $value;
    public $placeholder;
    public $searchPlaceholder;

    public $query = '';

    public function mount(
        $open = false,
        $id = 'programme-revision-typeahead',
        $name = 'programme_revision_id',
        $limit = 15,
        $value = null,
        $multiple = false,
        $placeholder = '',
        $searchPlaceholder = ''
    ) {
        $this->open = $open;
        $this->input_id = $id;
        $this->name = $name;
        $this->multiple = $multiple;
        $this->limit = $limit;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->searchPlaceholder = $searchPlaceholder;
    }

    public function render()
    {
        return view('livewire.typeahead-programmes-latest-revision', [
            'programmes' => $this->getProgrammes(),
        ]);
    }

    protected function getProgrammes()
    {
        return Programme::query()
            ->select(['id', 'code', 'name'])
            ->withLatestRevisionId()
            ->where('code', 'like', $this->query . '%')
            ->orWhere('name', 'like', '%' . $this->query . '%')
            ->get();
    }

    public function updatedQuery()
    {
        $this->open = true;
    }
}
