<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddRemove extends Component
{
    public $view;
    public $viewDataName;
    public $items;

    public function mount($view, $viewDataName = 'data', $items = [])
    {
        $this->view = $view;
        $this->viewDataName = $viewDataName;
        $this->items = $items;
    }

    public function render()
    {
        return view($this->view, [
            $this->viewDataName => $this->items,
        ]);
    }

    public function add($item = null)
    {
        $this->items[] = $item;
    }

    public function remove($index)
    {
        array_splice($this->items, $index, 1);
    }
}
