<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddRemove extends Component
{
    public $view;
    public $itemsName;
    public $items;
    public $newItem;
    public $data;

    public function mount($view, $itemsName = 'items', $items = [], $newItem = null, $data = [])
    {
        $this->view = $view;
        $this->itemsName = $itemsName;
        $this->items = $items;
        $this->newItem = $newItem;
        $this->data = $data;
    }

    public function render()
    {
        return view($this->view, array_merge($this->data, [
            $this->itemsName => $this->items,
        ]));
    }

    public function add()
    {
        $this->items[] = $this->newItem;
    }

    public function remove($index)
    {
        array_splice($this->items, $index, 1);
    }
}
