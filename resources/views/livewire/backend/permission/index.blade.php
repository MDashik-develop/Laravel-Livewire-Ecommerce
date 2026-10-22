<section class="space-y-5" x-data="{}" @scroll-to-role-editor.window="document.getElementById('role-editor-card')?.scrollIntoView({ behavior: 'smooth', block: 'start' })">
    <livewire:utilities.toast-modal />

    <!-- Breadcrumbs & Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <x-admin.breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Roles & Permissions']
            ]" />
            <div class="flex items-center gap-2.5 mt-2">
                <div class="w-8 h-8 rounded-[3px] bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-500/20">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold text-slate-900 dark:text-zinc-100 leading-tight">Roles & Permissions Management</h1>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Configure administrative access, define role hierarchies, and assign granular permissions.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-admin.button type="button" @click="document.getElementById('role-editor-card')?.scrollIntoView({ behavior: 'smooth' })" variant="primary" icon="plus" size="sm">
                New Role
            </x-admin.button>
        </div>
    </div>

    <!-- Quick Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="p-3.5 rounded-[4px] bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-zinc-400">Total Users</p>
                <h3 class="text-lg font-bold text-slate-900 dark:text-zinc-100 mt-0.5">{{ count($users) }}</h3>
                <span class="text-[10px] text-slate-400">Registered system accounts</span>
            </div>
            <div class="w-9 h-9 rounded-[3px] bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center border border-sky-200 dark:border-sky-900/60">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
        </div>

        <div class="p-3.5 rounded-[4px] bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-zinc-400">Defined Roles</p>
                <h3 class="text-lg font-bold text-slate-900 dark:text-zinc-100 mt-0.5">{{ count($roles) }}</h3>
                <span class="text-[10px] text-slate-400">Active permission profiles</span>
            </div>
            <div class="w-9 h-9 rounded-[3px] bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200 dark:border-emerald-900/60">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
            </div>
        </div>

        <div class="p-3.5 rounded-[4px] bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-zinc-400">Total Permissions</p>
                <h3 class="text-lg font-bold text-slate-900 dark:text-zinc-100 mt-0.5">
                    {{ collect($groupPermissions)->flatten(1)->count() }}
                </h3>
                <span class="text-[10px] text-slate-400">Across {{ count($groupPermissions) }} functional modules</span>
            </div>
            <div class="w-9 h-9 rounded-[3px] bg-brand-50 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-200 dark:border-brand-900/60">
                <i data-lucide="key" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Create New Permission Card (Development/Local Only - Hidden in Production) -->
    @if(!app()->isProduction())
    <x-admin.card class="border-amber-200/70 dark:border-amber-900/40 bg-gradient-to-r from-amber-500/[0.02] to-transparent">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-[2px] bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <i data-lucide="shield-plus" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">Create New Permission</h2>
                    <p class="text-[11px] text-slate-400">Register atomic capabilities for use in roles and route gates.</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[2px] text-[10px] font-semibold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20" title="This section is hidden automatically in production environment">
                <i data-lucide="code-2" class="w-3 h-3"></i>
                Dev Mode Only
            </span>
        </div>

        <form wire:submit.prevent="createPermission" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-5">
                <x-admin.input 
                    wire:model.defer="newPermissionName"
                    label="Permission Name"
                    placeholder="e.g. view, create, edit, delete" />
                <p class="text-[10px] text-slate-400 mt-1">Stored in format: <code class="text-brand-600 dark:text-brand-400">&lt;group&gt;.&lt;name&gt;</code></p>
            </div>

            <div class="md:col-span-4">
                <x-admin.select wire:model.live="selectedGroup" label="Module / Group">
                    <option value="">-- Select Module Group --</option>
                    @foreach($existingGroups as $group)
                        <option value="{{ $group }}">{{ ucfirst($group) }}</option>
                    @endforeach
                    <option value="__new">+ Create New Group...</option>
                </x-admin.select>

                @if($selectedGroup === '__new')
                    <div class="mt-2">
                        <x-admin.input wire:model.defer="newGroupName" placeholder="Enter new group name (e.g. product)" />
                    </div>
                @endif
            </div>

            <div class="md:col-span-3">
                <x-admin.button type="submit" variant="primary" icon="plus" class="w-full">
                    <span wire:loading.remove wire:target="createPermission">Create Permission</span>
                    <span wire:loading wire:target="createPermission">Creating...</span>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
    @endif

    <!-- 2 Columns: Assign Roles (Left) & Existing Roles Table (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        
        <!-- Col 1: Assign Roles to User with Searchable Selector (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <x-admin.card class="relative">
                <!-- Header -->
                <div class="flex items-center justify-between pb-2.5 mb-3 border-b border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-[2px] bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                            <i data-lucide="user-cog" class="w-3.5 h-3.5"></i>
                        </div>
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">Assign Roles to User</h2>
                            <p class="text-[11px] text-slate-400">Search user and grant administrative roles.</p>
                        </div>
                    </div>
                    @if($selectedUserId)
                        <x-admin.badge variant="success">User Active</x-admin.badge>
                    @endif
                </div>

                <div class="space-y-3.5">
                    @if(!$selectedUserId)
                        <!-- Search Input for User -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1">
                                Search User
                            </label>
                            <div class="relative">
                                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none"></i>
                                <input 
                                    type="text" 
                                    wire:model.live.debounce.250ms="userSearch"
                                    placeholder="Type name or email to search..."
                                    class="w-full pl-9 pr-8 py-2 text-xs bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-[3px] text-slate-800 dark:text-zinc-100 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                                
                                @if($userSearch)
                                    <button type="button" wire:click="$set('userSearch', '')" class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Filtered Users Selection List -->
                        <div class="space-y-1.5 max-h-64 overflow-y-auto pr-1 border border-slate-100 dark:border-zinc-800 rounded-[3px] p-1.5 bg-slate-50/50 dark:bg-zinc-900/40">
                            @forelse($this->filteredUsers as $u)
                                <div wire:key="user-select-{{ $u->id }}"
                                     wire:click="selectUser({{ $u->id }})"
                                     class="flex items-center justify-between p-2 rounded-[3px] border border-transparent hover:border-brand-500/30 hover:bg-white dark:hover:bg-zinc-800 cursor-pointer transition group">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-7 h-7 rounded-[3px] bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-200 font-bold text-[11px] flex items-center justify-center shrink-0 group-hover:bg-brand-500 group-hover:text-white transition">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-zinc-100 truncate group-hover:text-brand-600 dark:group-hover:text-brand-400">
                                                {{ $u->name }}
                                            </p>
                                            <p class="text-[11px] text-slate-400 truncate">
                                                {{ $u->email }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-1.5">
                                        @if($u->roles->isNotEmpty())
                                            <span class="px-1.5 py-0.5 rounded-[2px] text-[10px] font-medium bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-300 border border-brand-200 dark:border-brand-900">
                                                {{ $u->roles->first()->name }}
                                                @if($u->roles->count() > 1)
                                                    +{{ $u->roles->count() - 1 }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-[10px] text-slate-400 italic">No roles</span>
                                        @endif
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-brand-500 transition"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="py-6 text-center text-slate-400">
                                    <i data-lucide="user-x" class="w-6 h-6 mx-auto mb-1 text-slate-300 dark:text-zinc-600"></i>
                                    <p class="text-xs">No users found matching "{{ $userSearch }}"</p>
                                </div>
                            @endforelse
                        </div>
                    @else
                        <!-- Selected User Information Card -->
                        @php
                            $selectedUser = $this->selectedUser;
                        @endphp
                        <div class="p-3 rounded-[3px] border border-brand-500/30 bg-brand-50/20 dark:bg-brand-950/20 flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-[3px] bg-brand-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($selectedUser?->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100 truncate">{{ $selectedUser?->name }}</h4>
                                        <span class="text-[10px] px-1 py-0.2 rounded-[2px] bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20">Active</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 truncate">{{ $selectedUser?->email }}</p>
                                </div>
                            </div>
                            <x-admin.button type="button" wire:click="clearSelectedUser" variant="ghost" size="xs" icon="user-minus" title="Switch User">
                                Change
                            </x-admin.button>
                        </div>

                        <!-- Roles Checklist -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold text-slate-700 dark:text-zinc-300">
                                    Select Roles to Grant
                                </label>
                                <div class="flex items-center gap-2 text-[11px]">
                                    <button type="button" wire:click="selectAllRolesForUser" class="text-brand-600 dark:text-brand-400 hover:underline">
                                        Select All
                                    </button>
                                    <span class="text-slate-300">|</span>
                                    <button type="button" wire:click="deselectAllRolesForUser" class="text-slate-500 hover:underline">
                                        Clear
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-1.5 max-h-56 overflow-y-auto p-2.5 rounded-[3px] border border-slate-200 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-900/50">
                                @forelse($roles as $role)
                                    @php
                                        $isChecked = in_array($role->name, $userRoles);
                                    @endphp
                                    <label class="flex items-center justify-between p-2 rounded-[3px] border cursor-pointer transition {{ $isChecked ? 'border-brand-500/40 bg-brand-50/40 dark:bg-brand-950/30' : 'border-slate-200/70 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-slate-300' }}">
                                        <div class="flex items-center gap-2.5">
                                            <input type="checkbox" wire:model="userRoles" value="{{ $role->name }}"
                                                class="rounded-[2px] border-slate-300 dark:border-zinc-700 text-brand-600 focus:ring-brand-500/20">
                                            <span class="text-xs font-bold {{ $isChecked ? 'text-brand-700 dark:text-brand-300' : 'text-slate-800 dark:text-zinc-200' }}">
                                                {{ $role->name }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $role->permissions->count() }} permissions
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-[11px] text-slate-400 p-2 text-center">No roles defined yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2 flex items-center gap-2">
                            <x-admin.button wire:click="assignRolesToUser" variant="primary" icon="check" class="w-full">
                                <span wire:loading.remove wire:target="assignRolesToUser">Save User Roles</span>
                                <span wire:loading wire:target="assignRolesToUser">Updating Roles...</span>
                            </x-admin.button>
                        </div>
                    @endif
                </div>
            </x-admin.card>
        </div>

        <!-- Col 2: Existing Roles & Permissions Table (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <x-admin.card class="p-0 overflow-hidden">
                <!-- Header -->
                <div class="p-3.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-900/40 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-[2px] bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 flex items-center justify-center">
                            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                        </div>
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">Existing Roles & Permissions</h2>
                            <p class="text-[11px] text-slate-400">All registered roles and their assigned permission scopes.</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-[2px] text-[10px] font-bold bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300">
                        {{ count($roles) }} Active Roles
                    </span>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-800 text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-zinc-900/60 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                            <tr>
                                <th class="px-4 py-2.5 w-40">Role Name</th>
                                <th class="px-4 py-2.5">Permissions Granted</th>
                                <th class="px-4 py-2.5 text-right w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                            @forelse($roles as $role)
                                @php
                                    $isSuperAdmin = in_array(strtolower($role->name), ['super admin', 'super-admin']);
                                    $permCount = $role->permissions->count();
                                @endphp
                                <tr wire:key="role-row-{{ $role->id }}" class="hover:bg-slate-50/80 dark:hover:bg-zinc-800/40 transition">
                                    <td class="px-4 py-3 align-top whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-xs text-slate-900 dark:text-zinc-100">
                                                {{ $role->name }}
                                            </span>
                                            @if($isSuperAdmin)
                                                <span class="px-1.5 py-0.2 rounded-[2px] text-[9px] font-bold uppercase tracking-wide bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                    Master
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">
                                            {{ $permCount }} permissions
                                        </span>
                                    </td>
                                    
                                    <td class="px-4 py-3 align-top" x-data="{ expanded: false }">
                                        @if($permCount > 0)
                                            <div class="flex flex-wrap gap-1 items-center">
                                                @foreach($role->permissions->take(6) as $permission)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-[2px] text-[10px] font-medium bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach

                                                @if($permCount > 6)
                                                    <template x-if="expanded">
                                                        <div class="flex flex-wrap gap-1 contents">
                                                            @foreach($role->permissions->skip(6) as $permission)
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-[2px] text-[10px] font-medium bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">
                                                                    {{ $permission->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </template>

                                                    <button type="button" @click="expanded = !expanded" class="inline-flex items-center px-1.5 py-0.5 rounded-[2px] text-[10px] font-bold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/40 border border-brand-200 dark:border-brand-900 hover:underline">
                                                        <span x-text="expanded ? 'Show Less' : '+{{ $permCount - 6 }} more'"></span>
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">No permissions assigned.</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 align-top whitespace-nowrap text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <x-admin.button wire:click="editRole({{ $role->id }})" icon="pencil" size="xs" variant="secondary" title="Edit Role" />
                                            @if(!$isSuperAdmin)
                                                <x-admin.button wire:click="setRoleIdToDelete({{ $role->id }})" 
                                                    @click="$dispatch('modal-show', { name: 'delete-role-modal' })" 
                                                    icon="trash-2" size="xs" variant="danger" title="Delete Role" />
                                            @else
                                                <span class="p-1.5 text-slate-300 dark:text-zinc-600" title="System role cannot be deleted">
                                                    <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-xs text-slate-500 dark:text-zinc-400">
                                        No roles have been created yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-admin.card>
        </div>
    </div>

    <!-- Create Or Edit Role & Permissions Matrix Section -->
    <x-admin.card id="role-editor-card" class="space-y-4 scroll-mt-20">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 dark:border-zinc-800 gap-2">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-[3px] bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="{{ $editingRoleId ? 'edit-3' : 'plus-circle' }}" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">
                        {{ $editingRoleId ? 'Edit Role: ' . $newRoleName : 'Create New Role' }}
                    </h2>
                    <p class="text-[11px] text-slate-400">Assign role name and configure module permissions.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($editingRoleId)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[2px] text-[10px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20">
                        Editing Mode
                    </span>
                    <x-admin.button wire:click="cancelEdit" variant="ghost" size="xs" icon="x">
                        Cancel Edit
                    </x-admin.button>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <!-- Role Name Input -->
            <div class="max-w-md">
                <x-admin.input wire:model.defer="newRoleName" label="Role Name" placeholder="e.g. Sales Manager, Content Editor, Support Agent" />
            </div>

            <!-- Permission Groups Selector Matrix -->
            <div x-data="{ 
                matrixSearch: '',
                isSearching() {
                    return this.matrixSearch.trim().length > 0;
                }
            }" class="space-y-3 pt-2">
                
                <!-- Matrix Controls Toolbar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-2.5 rounded-[3px] bg-slate-50 dark:bg-zinc-900/60 border border-slate-200 dark:border-zinc-800">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">
                            Permissions Matrix
                        </span>
                        <span class="text-[10px] px-1.5 py-0.2 rounded-[2px] bg-brand-500/10 text-brand-600 dark:text-brand-400 font-semibold">
                            {{ count($newRolePermissions) }} Selected
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Quick Select All / Clear All Buttons -->
                        <div class="inline-flex items-center gap-1.5 text-xs">
                            <button type="button" wire:click="selectAllPermissions" class="px-2 py-1 rounded-[2px] text-[11px] font-semibold bg-white dark:bg-zinc-800 text-brand-600 dark:text-brand-400 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50">
                                Select All Matrix
                            </button>
                            <button type="button" wire:click="deselectAllPermissions" class="px-2 py-1 rounded-[2px] text-[11px] font-semibold bg-white dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50">
                                Deselect All
                            </button>
                        </div>

                        <!-- Live Filter Matrix Input -->
                        <div class="relative w-full sm:w-56">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2 pointer-events-none"></i>
                            <input x-model="matrixSearch" type="text" placeholder="Filter permissions..."
                                class="w-full pl-8 pr-6 py-1 text-xs bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-[3px] text-slate-800 dark:text-zinc-100 placeholder-slate-400 focus:outline-none focus:border-brand-500">
                            <button x-show="isSearching()" @click="matrixSearch = ''" type="button" class="absolute right-2 top-1.5 text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Groups Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                    @foreach ($groupPermissions as $group => $permissions)
                        @php
                            $groupName = $group ?: 'Ungrouped';
                            $allPermValues = collect($permissions)->pluck('full_name')->toJson();
                            $groupCount = count($permissions);
                        @endphp
                        <div x-data="{
                                allInGroup: {{ $allPermValues }},
                                matchesSearch() {
                                    if (!matrixSearch.trim()) return true;
                                    const q = matrixSearch.toLowerCase();
                                    if ('{{ strtolower($groupName) }}'.includes(q)) return true;
                                    return this.allInGroup.some(p => p.toLowerCase().includes(q));
                                }
                            }"
                            x-show="matchesSearch()"
                            class="p-3 rounded-[3px] border border-slate-200 dark:border-zinc-700 bg-slate-50/40 dark:bg-zinc-900/40 flex flex-col justify-between transition hover:border-slate-300 dark:hover:border-zinc-600">
                            
                            <!-- Group Card Header -->
                            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200 dark:border-zinc-800">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-[1px] bg-brand-500"></span>
                                    <span class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-zinc-200">
                                        {{ ucfirst($groupName) }}
                                    </span>
                                </div>
                                
                                <button type="button" wire:click="toggleGroupPermissions('{{ $group }}')" class="inline-flex items-center gap-1 text-[11px] text-brand-600 dark:text-brand-400 font-semibold hover:underline">
                                    Toggle Group
                                </button>
                            </div>

                            <!-- Group Checkboxes List -->
                            <div class="py-1 space-y-1.5 max-h-48 overflow-y-auto">
                                @forelse($permissions as $permission)
                                    @php
                                        $pChecked = in_array($permission['full_name'], $newRolePermissions);
                                    @endphp
                                    <label x-show="!matrixSearch.trim() || '{{ strtolower($permission['full_name']) }}'.includes(matrixSearch.toLowerCase()) || '{{ strtolower($groupName) }}'.includes(matrixSearch.toLowerCase())"
                                           class="flex items-center justify-between p-1.5 rounded-[2px] cursor-pointer text-xs transition {{ $pChecked ? 'bg-brand-50/50 dark:bg-brand-950/30 text-brand-700 dark:text-brand-300' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <input type="checkbox" 
                                                value="{{ $permission['full_name'] }}"
                                                wire:model="newRolePermissions"
                                                class="rounded-[2px] border-slate-300 dark:border-zinc-700 text-brand-600 focus:ring-brand-500/20">
                                            <span class="truncate font-medium">{{ $permission['short_name'] }}</span>
                                        </div>
                                        <span class="text-[9px] text-slate-400 shrink-0 font-mono">
                                            {{ $permission['full_name'] }}
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-[11px] text-slate-400 py-2 text-center">No permissions in this group.</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center gap-2 border-t border-slate-100 dark:border-zinc-800">
                @if($editingRoleId)
                    <x-admin.button wire:click="updateRole" variant="primary" icon="check">
                        <span wire:loading.remove wire:target="updateRole">Save Role Changes</span>
                        <span wire:loading wire:target="updateRole">Updating...</span>
                    </x-admin.button>
                    <x-admin.button wire:click="cancelEdit" variant="ghost">
                        Cancel
                    </x-admin.button>
                @else
                    <x-admin.button wire:click="createRole" variant="primary" icon="plus">
                        <span wire:loading.remove wire:target="createRole">Create New Role</span>
                        <span wire:loading wire:target="createRole">Creating...</span>
                    </x-admin.button>
                    <x-admin.button type="button" wire:click="deselectAllPermissions" variant="ghost">
                        Reset Form
                    </x-admin.button>
                @endif
            </div>
        </div>
    </x-admin.card>

    <!-- DELETE CONFIRMATION MODAL -->
    <x-admin.modal name="delete-role-modal" title="Delete Role" maxWidth="max-w-md">
        <div class="space-y-4 text-center">
            <div class="w-10 h-10 rounded-[3px] bg-rose-50 dark:bg-rose-950/50 text-rose-600 mx-auto flex items-center justify-center border border-rose-200 dark:border-rose-900">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Delete this role?</h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">This role will be permanently removed and detached from any users. This action cannot be undone.</p>
            </div>
            <div class="flex justify-center gap-2 pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'delete-role-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button wire:click="deleteRole" variant="danger">
                    <span wire:loading.remove wire:target="deleteRole">Delete Role</span>
                    <span wire:loading wire:target="deleteRole">Deleting...</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</section>