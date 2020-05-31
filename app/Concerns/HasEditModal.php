<?php

namespace App\Concerns;

trait HasEditModal
{
    public $modalName;
    public $showModal = false;

    public function getListeners()
    {
        return array_merge($this->listeners, ['show']);
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function show($data)
    {
        $this->beforeShow($data);
        $this->showModal = true;
    }

    public function beforeShow($data)
    {
    }
}
