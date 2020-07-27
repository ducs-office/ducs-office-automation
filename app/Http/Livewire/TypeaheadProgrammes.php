<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\Programme;
use Livewire\Component;

class TypeaheadProgrammes extends Component
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
        $id = 'programme-typeahead',
        $name = 'programme_id',
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
        return view('livewire.typeahead-programmes', [
            'programmes' => $this->getProgrammes(),
        ]);
    }

    protected function getProgrammes()
    {
        $programmes = Programme::select(['id', 'code', 'name']);

        if ($this->query && $this->query != '') {
            $programmes->where(function ($builder) {
                $builder->where('code', 'like', $this->query . '%')
                    ->orWhere('name', 'like', '%' . $this->query . '%');
            })
            ->orWhereIn('id', is_array($this->value) ? $this->value : [$this->value]);
        }

        return $programmes->limit($this->limit)->get();
    }

    public function updatedQuery()
    {
        $this->open = true;
    }
}
