<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class FooMultiTest extends Component
{
    public $searchQuery = '';
    public $value;
    public $name;
    protected $limit;

    public function mount($name = '', $value = [1, 5], $limit = 10)
    {
        $this->name = $name;
        $this->limit = $limit;
        $this->value = $value;
    }

    public function render()
    {
        return view('livewire.foo-multi-test', $this->getData($this->searchQuery));
    }

    protected function getData($search)
    {
        return [
            'users' => User::query()
                ->where(function ($query) use ($search) {
                    if ($search != '') {
                        $query->where('first_name', 'like', $search . '%')
                            ->orWhere('last_name', 'like', $search . '%');
                    }
                    if (count($this->value) > 0) {
                        $query->orWhereIn('id', $this->value);
                    }
                })
                ->limit($this->limit)->get(),
        ];
    }

    public function updatedValue()
    {
        $this->searchQuery = '';
    }
}
