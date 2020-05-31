<?php

namespace App\Concerns;

use App\Models\Course;
use Illuminate\Support\Str;

trait HasEditModal
{
    public $modalName;
    public $showModal = false;

    public function mount($errorBag = null)
    {
        $this->listeners = ['show'];

        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = Str::kebab(class_basename($this));

        parent::mount($errorBag);
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
