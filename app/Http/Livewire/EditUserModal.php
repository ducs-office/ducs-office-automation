<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class EditUserModal extends Component
{
    protected $listeners = ['show'];
    public $showModal = false;
    public $modalName;

    protected $user;
    public $roles;

    public function mount($roles)
    {
        $this->modalName = Str::kebab(class_basename($this));
        $this->user = new User();
        $this->roles = $roles;
    }

    public function render()
    {
        return view('livewire.edit-user-modal', [
            'categories' => UserCategory::values(),
            'user' => $this->user,
        ]);
    }

    public function show($userId)
    {
        $this->user = User::find($userId);
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }
}
