<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class GroupedPermissionInput extends Component
{
    public $permissions;
    public $fieldName;
    public $role;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $permissions, $old = [], $role = null)
    {
        $this->fieldName = $name;
        $this->permissions = $permissions;
        $this->role = $role;
        $this->old = $old;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.grouped-permission-input');
    }

    public function isViewPermission($permission)
    {
        return Str::endsWith($permission->name, ':view');
    }

    public function getAction($permission)
    {
        return Str::after($permission->name, ':');
    }

    public function getResource($permission)
    {
        return Str::before($permission->name, ':');
    }

    public function getViewPermission()
    {
        return $this->permissions->filter(function ($permission) {
            return $this->getAction($permission) === 'view';
        })->first();
    }

    public function isSelected($permission)
    {
        return in_array($permission->id, $this->old)
            || ($this->role && $this->role->hasPermissionTo($permission));
    }

    public function selectedMap()
    {
        return $this->permissions->mapWithKeys(function ($permission) {
            return [$permission->id => $this->isSelected($permission)];
        })->all();
    }
}
