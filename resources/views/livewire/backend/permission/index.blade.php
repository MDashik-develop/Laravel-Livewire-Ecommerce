<section>

    <livewire:utilities.toast-modal />

    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 bg-white shadow rounded-lg dark:bg-zinc-800">

        <header class="mb-10">
            <h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-gray-100">Management Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Easily manage users, roles, and permissions.</p>
        </header>






        <!-- Create New Permission -->
        <div class="bg-gray-50 dark:bg-zinc-700 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-600">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Create New Permission</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Add a new permission to the system.</p>

            <div class="space-y-4">
                <!-- Permission Name -->
                <div>
                    <label for="new_permission"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Permission Name</label>
                    <input type="text" id="new_permission" wire:model.defer="newPermissionName"
                        placeholder="e.g., create-product"
                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-600 border border-gray-300 dark:border-zinc-500 rounded-md">
                    <p class="mt-2 text-xs text-gray-500">Use lowercase and hyphens (e.g., edit-post).</p>
                    @error('newPermissionName')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Permission Group -->
                <div>
                    <label for="permission_group"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Permission Group</label>

                    <select id="permission_group" wire:model="selectedGroup"
                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-600 border border-gray-300 dark:border-zinc-500 rounded-md">
                        <option value="">-- Select Group --</option>
                        @foreach($existingGroups as $group)
                            <option value="{{ $group }}">{{ ucfirst($group) }}</option>
                        @endforeach
                        <option value="__new">+ Create New Group</option>
                    </select>

                    <!-- Show new group input only if user selected "__new" -->
                    @if($selectedGroup === '__new')
                        <input type="text" wire:model.defer="newGroupName" placeholder="Enter new group name"
                            class="mt-2 block w-full px-3 py-2 bg-white dark:bg-zinc-600 border border-gray-300 dark:border-zinc-500 rounded-md">
                        @error('newGroupName')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    @endif
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <flux:button wire:click="createPermission" variant="primary" icon="plus-circle" class="w-full">
                        Create Permission
                    </flux:button>
                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 my-4">

            <!-- Column 1: Assign Roles to User -->
            <div class="lg:col-span-1 bg-gray-50 dark:bg-zinc-700 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-600"
                wire:loading.class.delay="opacity-50" wire:target="selectedUserId">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Assign Roles to User</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Select a user and assign the necessary roles.
                </p>
                <div class="mb-6">
                    <label for="user" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Select a
                        User</label>
                    <select id="user" wire:model.live="selectedUserId"
                        class="block w-full px-3 py-2 bg-white dark:bg-zinc-600 border border-gray-300 dark:border-zinc-500 rounded-md shadow-sm">
                        <option value="">-- Select a User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                @if($selectedUserId)
                    <div>
                        <h3 class="text-md font-medium text-gray-700 dark:text-gray-200 mb-3">Roles</h3>
                        <div
                            class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar pr-2 border rounded-md p-4 bg-white dark:bg-zinc-800">
                            @forelse($roles as $role)
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="role_{{ $role->id }}" type="checkbox" wire:model="userRoles"
                                            value="{{ $role->name }}"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="role_{{ $role->id }}"
                                            class="font-medium text-gray-700 dark:text-gray-200">{{ $role->name }}</label>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No roles found.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="mt-6">
                        <flux:button wire:click="assignRolesToUser" variant="primary" icon="pencil-square" class="w-full">
                            Assign Roles
                        </flux:button>
                    </div>
                @endif
            </div>

            <!-- Column 2: Create/Edit Role, Permission & Manage -->
            <div class="lg:col-span-2">

                <!-- Existing Roles & Permissions Table -->
                <div class="bg-gray-50 dark:bg-zinc-700 min-h-full rounded-xl overflow-auto shadow-sm border border-gray-200 dark:border-zinc-600">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Existing Roles &
                            Permissions</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">View all roles and their assigned
                            permissions.</p>
                    </div>
                    <div class="overflow-x-auto rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-600">
                            <thead class="bg-gray-100 dark:bg-zinc-800">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Role</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Permissions</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-700 divide-y divide-gray-200 dark:divide-zinc-600">
                                @forelse($roles as $role)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $role->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div class="flex flex-wrap gap-2">
                                                @forelse($role->permissions as $permission)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $permission->name }}</span>
                                                @empty
                                                    <span class="text-xs text-gray-400">No permissions assigned.</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">

                                            <flux:button href="#update" variant="filled"
                                                wire:click="editRole({{ $role->id }})" icon="pencil-square">
                                            </flux:button>

                                            <!-- CORRECTED DELETE TRIGGER -->
                                            <flux:modal.trigger name="delete-role-modal"
                                                wire:click="setRoleIdToDelete({{ $role->id }})">
                                                <flux:button variant="danger" icon="trash"></flux:button>
                                            </flux:modal.trigger>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">No roles
                                            have been created yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-1 gap-8">
            <!-- Create Or Edit Role Form -->
            <div class="bg-gray-50 dark:bg-zinc-700 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-600">
                @if($editingRoleId)
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Edit Role</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Modify the role name and its permissions.</p>
                @else
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Create New Role</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Define a new role and assign permissions.</p>
                @endif
                <div class="space-y-4">
                    <div>
                        <label for="new_role" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Role
                            Name</label>
                        <input type="text" id="new_role" wire:model.defer="newRoleName" placeholder="e.g., Manager"
                            class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-600 border border-gray-300 dark:border-zinc-500 rounded-md">
                        @error('newRoleName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Permissions</h3>

                        <div class=<div x-data="{ search: '' }" class="p-4 sm:p-6 bg-slate-50 dark:bg-slate-900 font-sans">
                            <!-- Header and Search -->
                            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-200">Manage Role Permissions</h2>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Select the permissions for this role from the groups
                                        below.</p>
                                </div>
                                <div class="mt-4 relative">
                                    <input x-model="search" type="text" placeholder="Search permission groups..."
                                        class="w-full max-w-xs pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Permissions Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 md:gap-4 lg:gap-6">
                                @foreach ($groupPermissions as $group => $permissions)
                                    <div x-show="search === '' || '{{ strtolower($group) }}'.includes(search.toLowerCase())"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        class="bg-white dark:bg-zinc-600 p-6 dark:border-zinc-500 rounded-xl shadow-sm border border-slate-200  flex flex-col transition-all duration-200 hover:shadow-md hover:border-zinc-800/50">
                                        <flux:checkbox.group class="flex flex-col flex-1">
                                            <!-- Card Header -->
                                            <div class="p-4 border-b border-slate-200 dark:border-zin-500 flex items-center justify-between ">
                                                <flux:legend class="font-semibold text-base text-slate-800 dark:text-slate-200">
                                                    {{ ucfirst($group) ?? 'Ungrouped' }} 
                                                </flux:legend>
                                                <flux:checkbox.all label="Select All" />
                                            </div>

                                           <!-- Card Body -->
                                        <div class="p-5 flex-1 max-h-64 overflow-y-auto custom-scrollbar">
                                            <flux:fieldset>
                                                <div class="grid grid-cols-2 xl:grid-cols-3 gap-3">
                                                    @forelse($permissions as $permission)
                                                        <flux:checkbox 
                                                            value="{{ $permission['full_name'] }}"
                                                            label="{{ $permission['short_name'] }}"
                                                            wire:model.defer="newRolePermissions"
                                                            class="text-nowrap whitespace-nowrap flex items-center"
                                                        />
                                                    @empty
                                                        <div class="flex items-center justify-center h-full text-center col-span-2">
                                                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                                                No permissions found in this group.
                                                            </p>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </flux:fieldset>
                                        </div>

                                        </flux:checkbox.group>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <div class="pt-2 flex space-x-2">
                        @if($editingRoleId)
                            <flux:button wire:click="updateRole" variant="primary" icon="pencil-square" class=" w-50">
                                Update Role
                            </flux:button>
                            <flux:button wire:click="cancelEdit" icon="no-symbol" class=" w-50">
                                Cancel
                            </flux:button>

                        @else
                            <!-- <button wire:click="createRole" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">Create Role</button> -->
                            <flux:button wire:click="createRole" variant="primary" icon="plus" class="w-full">
                                Create Role
                            </flux:button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <flux:modal name="delete-role-modal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Role?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this role.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteRole" variant="danger">
                    <span wire:loading.remove wire:target="deleteRole">Delete Role</span>
                    <span wire:loading wire:target="deleteRole">Deleting...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</section>