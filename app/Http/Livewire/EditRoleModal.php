<?php

namespace App\Http\Livewire;

use App\Concerns\HasEditModal;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditRoleModal extends Component
{
    use HasEditModal;

    protected $role;

    public function mount($modalName = null, $errorBag = null)
    {
        if ($errorBag != null) {
            $this->setErrorBag($errorBag);
        }

        $this->modalName = $modalName ?? Str::kebab(class_basename($this));

        $this->role = new Role();
        if (! $this->getErrorBag()->isEmpty() && old('role_id', null)) {
            $this->show(old('role_id'));
        }
    }

    public function render()
    {
        return view('livewire.edit-role-modal', [
            'role' => $this->role,
            'permissions' => Permission::all()->groupBy(function ($permission) {
                return Str::title(explode(':', $permission->name)[0]);
            }),
        ]);
    }

    public function beforeShow($roleId)
    {
        $this->role = Role::find($roleId);
    }
}
