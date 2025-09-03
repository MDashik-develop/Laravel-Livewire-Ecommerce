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

    public $users, $roles, $permissions, $groupPermissions, $selectedUserId, $newRoleName, $newPermissionName, $editingRoleId, $roleIdToDelete;
    public array $userRoles = [];
    public array $newRolePermissions = [];
    public string $selectedGroup = '';
    public string $newGroupName = '';
    public array $existingGroups = [];

    public function mount()
    {
        $this->loadData();
        $this->existingGroups = Permission::select('group')
            ->distinct()
            ->pluck('group')
            ->filter()
            ->toArray();
    }

    protected function loadData()
    {
        $this->users = User::select('id', 'name', 'email')->get();
        $this->roles = Role::with('permissions')->orderBy('name')->get();
       
        $this->groupPermissions = Permission::all()
                                    ->groupBy('group')
                                    ->map(function ($permissions) {
                                        return $permissions->map(function ($perm) {
                                            return [
                                                    // 'id'   => $perm->id,
                                                    // 'full_name' => $perm->name, // DB er jonno
                                                    // 'short_name' => last(explode('.', $perm->name)), // UI er jonno

                                                    
                                                    'id' => $perm->id,
                                                    'full_name' => $perm->name, // DB value
                                                    'short_name' => str_contains($perm->name, '.') ? last(explode('.', $perm->name)) : $perm->name,
                                                    'group' => $group ?? 'Ungrouped',

                                            ];
                                        });
                                    })
                                    ->toArray();

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
        $this->validate([
            'newPermissionName' => 'required|string|unique:permissions,name',
            'selectedGroup' => 'required',
        ]);

        $group = $this->selectedGroup === '__new'
            ? $this->newGroupName
            : $this->selectedGroup;

        if ($this->selectedGroup === '__new') {
            $this->validate([
                'newGroupName' => 'required|string|max:50',
            ]);
        }

        Permission::create([
            'name' => strtolower($group . '.' . $this->newPermissionName), // e.g. category.edit,
            'group' => $group,
        ]);

        $this->reset(['newPermissionName', 'selectedGroup', 'newGroupName']);
        $this->existingGroups = Permission::select('group')
            ->distinct()
            ->pluck('group')
            ->filter()
            ->toArray();
            
        $this->loadData();
        
        $this->dispatch('show-toast', [
            'title' => 'Success 🎉',
            'message' => 'New permission created successfully!',
            'type' => 'success'
        ]);
    }
    
    // public function toggleGroupPermissions($group)
    // {
    //     if (!isset($this->groupPermissions[$group])) return;

    //     $groupPerms = collect($this->groupPermissions[$group])->pluck('full_name')->toArray();

    //     $allSelected = collect($groupPerms)->every(fn($perm) => in_array($perm, $this->newRolePermissions));

    //     if ($allSelected) {
    //         // Deselect all
    //         $this->newRolePermissions = array_values(array_diff($this->newRolePermissions, $groupPerms));
    //     } else {
    //         // Select all
    //         $this->newRolePermissions = array_unique(array_merge($this->newRolePermissions, $groupPerms));
    //     }

    //     // $this->emitSelf('updatedNewRolePermissions'); <-- Remove this line
    // }

    public function setRoleIdToDelete($roleId)
    {
        $this->roleIdToDelete = $roleId;
    }

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

        }
        $this->reset('roleIdToDelete');
    }
    

    public function render()
    {
        return view('livewire.backend.permission.index');
    }
}