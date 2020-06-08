<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class TypeaheadUsers extends Component
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
        $id = 'user-typeahead',
        $name = 'user_id',
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
        return view('livewire.typeahead-users', [
            'users' => $this->getUsers(),
        ]);
    }

    public function getErrorKeyProperty()
    {
        return preg_replace(['~\[\]~', '~\[([^\]]+)\]~'], ['.*', '.$1'], $this->name);
    }

    protected function getUsers()
    {
        return User::query()
            ->where('first_name', 'like', $this->query . '%')
            ->orWhere('last_name', 'like', $this->query . '%')
            ->orWhere('email', $this->query)
            ->orWhereIn('id', is_array($this->value) ? $this->value : [$this->value])
            ->get();
    }

    public function updatedQuery()
    {
        $this->open = true;
    }
}
