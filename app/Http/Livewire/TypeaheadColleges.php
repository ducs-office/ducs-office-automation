<?php

namespace App\Http\Livewire;

use App\Models\College;
use Livewire\Component;

class TypeaheadColleges extends Component
{
    public $open;
    public $input_id;
    public $name;
    public $limit;
    public $value;
    public $placeholder;
    public $searchPlaceholder;

    public $query = '';

    public function mount(
        $open = false,
        $id = 'college-typeahead',
        $name = 'college_id',
        $limit = 15,
        $value = null,
        $placeholder = '',
        $searchPlaceholder = ''
    ) {
        $this->open = $open;
        $this->input_id = $id;
        $this->name = $name;
        $this->limit = $limit;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->searchPlaceholder = $searchPlaceholder;
    }

    public function render()
    {
        return view('livewire.typeahead-colleges', [
            'colleges' => $this->getColleges(),
        ]);
    }

    protected function getColleges()
    {
        return College::query()
            ->where('name', 'like', $this->query . '%')
            ->orWhere('code', 'like', $this->query . '%')
            ->orWhereIn('id', is_array($this->value) ? $this->value : [$this->value])
            ->get();
    }

    public function updatedQuery()
    {
        $this->open = true;
    }
}
