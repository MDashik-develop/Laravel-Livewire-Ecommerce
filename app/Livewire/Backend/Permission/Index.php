<?php

namespace App\Livewire\Backend\Permission;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class Index extends Component
{
    // Toast notification state
    public array $toast = [];

    public $users, $roles, $permissions, $groupPermissions, $selectedUserId, $newRoleName, $newPermissionName, $editingRoleId, $roleIdToDelete;
    public string $userSearch = '';
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
        $this->users = User::select('id', 'name', 'email')->with('roles:id,name')->orderBy('name')->get();
        $this->roles = Role::with('permissions')->orderBy('name')->get();
       
        $this->groupPermissions = Permission::all()
            ->groupBy(fn($p) => $p->group ?: 'general')
            ->map(function ($permissions, $group) {
                return $permissions->map(function ($perm) use ($group) {
                    return [
                        'id' => $perm->id,
                        'full_name' => $perm->name,
                        'short_name' => str_contains($perm->name, '.') ? last(explode('.', $perm->name)) : $perm->name,
                        'group' => $group,
                    ];
                });
            })
            ->toArray();
    }

    public function getFilteredUsersProperty()
    {
        if (empty(trim($this->userSearch))) {
            return $this->users;
        }

        $term = strtolower(trim($this->userSearch));
        return $this->users->filter(function ($user) use ($term) {
            return str_contains(strtolower($user->name), $term)
                || str_contains(strtolower($user->email), $term);
        });
    }

    public function getSelectedUserProperty()
    {
        return $this->selectedUserId ? $this->users->firstWhere('id', $this->selectedUserId) : null;
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $user = User::find($userId);
        if ($user) {
            $this->userRoles = $user->getRoleNames()->toArray();
        } else {
            $this->userRoles = [];
        }
    }

    public function clearSelectedUser()
    {
        $this->selectedUserId = null;
        $this->userRoles = [];
        $this->userSearch = '';
    }

    public function selectAllRolesForUser()
    {
        $this->userRoles = $this->roles->pluck('name')->toArray();
    }

    public function deselectAllRolesForUser()
    {
        $this->userRoles = [];
    }

    public function updatedSelectedUserId($userId)
    {
        if (!empty($userId)) {
            $this->selectUser($userId);
        } else {
            $this->clearSelectedUser();
        }
    }

    public function assignRolesToUser()
    {
        $this->validate(['selectedUserId' => 'required|exists:users,id']);
        $user = User::find($this->selectedUserId);
        if ($user) {
            $user->syncRoles($this->userRoles);
            $this->loadData();
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
        $this->dispatch('scroll-to-role-editor');
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

    public function selectAllPermissions()
    {
        $all = [];
        foreach ($this->groupPermissions as $perms) {
            foreach ($perms as $p) {
                $all[] = $p['full_name'];
            }
        }
        $this->newRolePermissions = array_values(array_unique($all));
    }

    public function deselectAllPermissions()
    {
        $this->newRolePermissions = [];
    }

    public function toggleGroupPermissions(string $group)
    {
        if (!isset($this->groupPermissions[$group])) return;

        $groupPerms = collect($this->groupPermissions[$group])->pluck('full_name')->toArray();
        $allSelected = collect($groupPerms)->every(fn($perm) => in_array($perm, $this->newRolePermissions));

        if ($allSelected) {
            $this->newRolePermissions = array_values(array_diff($this->newRolePermissions, $groupPerms));
        } else {
            $this->newRolePermissions = array_values(array_unique(array_merge($this->newRolePermissions, $groupPerms)));
        }
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
            'name' => strtolower($group . '.' . $this->newPermissionName),
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

    public function setRoleIdToDelete($roleId)
    {
        $this->roleIdToDelete = $roleId;
    }

    public function deleteRole()
    {
        if ($this->roleIdToDelete) {
            $role = Role::findOrFail($this->roleIdToDelete);
            
            if (strtolower($role->name) === 'super admin' || strtolower($role->name) === 'super-admin') {
                $this->dispatch('show-toast', [
                    'title' => 'Protected Role ⚠️',
                    'message' => 'Super Admin role cannot be deleted!',
                    'type' => 'error'
                ]);
                $this->dispatch('modal-close', name: 'delete-role-modal');
                $this->reset('roleIdToDelete');
                return;
            }

            $role->delete();
            $this->loadData();
            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Role deleted successfully!',
                'type' => 'success'
            ]);
            $this->dispatch('modal-close', name: 'delete-role-modal');
        }
        $this->reset('roleIdToDelete');
    }

    public function render()
    {
        return view('livewire.backend.permission.index');
    }
}