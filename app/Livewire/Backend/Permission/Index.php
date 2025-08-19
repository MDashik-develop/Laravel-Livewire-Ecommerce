<?php

namespace App\Livewire\Backend\Permission;

use Flux\Flux;
use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class Index extends Component
{
    //Toast flux massage
    public array $toast = [];

    public $users, $roles, $permissions, $selectedUserId, $newRoleName, $newPermissionName, $editingRoleId, $roleIdToDelete;
    public array $userRoles = [];
    public array $newRolePermissions = [];

    public function mount()
    {
        $this->loadData();
    }

    protected function loadData()
    {
        $this->users = User::select('id', 'name', 'email')->get();
        $this->roles = Role::with('permissions')->orderBy('name')->get();
        $this->permissions = Permission::orderBy('name')->get();
    }

    public function updatedSelectedUserId($userId)
    {
        if (!empty($userId)) {
            $user = User::find($userId);
            if ($user) {
                $this->userRoles = $user->getRoleNames()->toArray();
            }
        } else {
            $this->reset('userRoles');
        }
    }

    public function assignRolesToUser()
    {
        $this->validate(['selectedUserId' => 'required|exists:users,id']);
        $user = User::find($this->selectedUserId);
        if ($user) {
            $user->syncRoles($this->userRoles);
            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'User roles updated successfully!',
                'type' => 'success'
            ]);
        }
    }

    public function createRole()
    {
        $this->validate([
            'newRoleName' => 'required|string|unique:roles,name',
            'newRolePermissions' => 'sometimes|array',
        ]);
        $role = Role::create(['name' => $this->newRoleName]);
        if (!empty($this->newRolePermissions)) {
            $role->syncPermissions($this->newRolePermissions);
        }
        $this->reset(['newRoleName', 'newRolePermissions']);
        $this->loadData();
        $this->dispatch('show-toast', [
            'title' => 'Success 🎉',
            'message' => 'New role created successfully!',
            'type' => 'success'
        ]);
        
    }

    public function editRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->editingRoleId = $role->id;
        $this->newRoleName = $role->name;
        $this->newRolePermissions = $role->permissions->pluck('name')->toArray();
    }

    public function updateRole()
    {
        $this->validate([
            'newRoleName' => ['required', 'string', Rule::unique('roles', 'name')->ignore($this->editingRoleId)],
            'newRolePermissions' => 'sometimes|array',
        ]);
        $role = Role::findOrFail($this->editingRoleId);
        $role->name = $this->newRoleName;
        $role->save();
        $role->syncPermissions($this->newRolePermissions);
        $this->cancelEdit();
        $this->loadData();
        $this->dispatch('show-toast', [
            'title' => 'Success 🎉',
            'message' => 'Role updated successfully!',
            'type' => 'success'
        ]);
    }

    public function cancelEdit()
    {
        $this->reset(['editingRoleId', 'newRoleName', 'newRolePermissions']);
    }

    public function createPermission()
    {
        $this->validate(['newPermissionName' => 'required|string|unique:permissions,name']);
        Permission::create(['name' => $this->newPermissionName]);
        $this->reset('newPermissionName');
        $this->loadData();
        $this->dispatch('show-toast', [
            'title' => 'Success 🎉',
            'message' => 'New permission created successfully!',
            'type' => 'success'
        ]);
    }

    /**
     * Sets the role ID to be deleted. This is called when the trigger is clicked.
     */
    public function setRoleIdToDelete($roleId)
    {
        $this->roleIdToDelete = $roleId;
    }

    /**
     * Deletes the role. This is called from the modal's confirmation button.
     */
    public function deleteRole()
    {
        if ($this->roleIdToDelete) {
            Role::findOrFail($this->roleIdToDelete)->delete();
            $this->loadData();
            // session()->flash('success', 'Role deleted successfully.');
            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Role deleted successfully!',
                'type' => 'success'
            ]);
            Flux::modal('delete-role-modal')->close();
            // Flux::modal('toast-modal')->show();

        }
        $this->reset('roleIdToDelete');
    }

    public function render()
    {
        return view('livewire.backend.permission.index');
    }
}