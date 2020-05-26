<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class FooTest extends Component
{
    public $searchQuery = '';
    public $value;
    public $name;
    protected $limit;

    public function mount($name = '', $value = 5, $limit = 10)
    {
        $this->name = $name;
        $this->limit = $limit;
        $this->value = $value;
    }

    public function render()
    {
        return view('livewire.foo-test', $this->getData($this->searchQuery));
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
                })
                ->orWhere('id', $this->value)
                ->limit($this->limit)->get(),
        ];
    }
}
