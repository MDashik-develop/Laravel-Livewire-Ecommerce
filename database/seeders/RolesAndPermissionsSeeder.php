<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
            // Category
                Permission::create(['name' => 'category.view', 'group' => 'category']);
                Permission::create(['name' => 'category.create', 'group' => 'category']);
                Permission::create(['name' => 'category.edit', 'group' => 'category']);
                Permission::create(['name' => 'category.delete', 'group' => 'category']);
            // SubCategory
                Permission::create(['name' => 'subcategory.view', 'group' => 'subcategory']);
                Permission::create(['name' => 'subcategory.create', 'group' => 'subcategory']);
                Permission::create(['name' => 'subcategory.edit', 'group' => 'subcategory']);
                Permission::create(['name' => 'subcategory.delete', 'group' => 'subcategory']);
            // Brand
                Permission::create(['name' => 'brand.view', 'group' => 'brand']);
                Permission::create(['name' => 'brand.create', 'group' => 'brand']);
                Permission::create(['name' => 'brand.edit', 'group' => 'brand']);
                Permission::create(['name' => 'brand.delete', 'group' => 'brand']);
            // Store
                Permission::create(['name' => 'store.view', 'group' => 'store']);
                Permission::create(['name' => 'store.create', 'group' => 'store']);
                Permission::create(['name' => 'store.edit', 'group' => 'store']);
                Permission::create(['name' => 'store.delete', 'group' => 'store']);
                Permission::create(['name' => 'store.approval', 'group' => 'store']);
                Permission::create(['name' => 'store.status', 'group' => 'store']);
            //Product
                Permission::create(['name' => 'product.view', 'group' => 'product']);
                Permission::create(['name' => 'product.create', 'group' => 'product']);
                Permission::create(['name' => 'product.edit', 'group' => 'product']);
                Permission::create(['name' => 'product.delete', 'group' => 'product']);
            //Banner
                Permission::create(['name' => 'banner.view', 'group' => 'banner']);
                Permission::create(['name' => 'banner.create', 'group' => 'banner']);
                Permission::create(['name' => 'banner.edit', 'group' => 'banner']);
                Permission::create(['name' => 'banner.delete', 'group' => 'banner']);
                

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create roles and assign created permissions

        // this can be done as separate statements
        // $role = Role::create(['name' => 'writer']);
        // $role->givePermissionTo('edit articles');

        // or may be done by chaining
        // $role = Role::create(['name' => 'moderator'])
        //     ->givePermissionTo(['publish articles', 'unpublish articles']);

        $role = Role::create(['name' => 'Super Admin']);
        // $role->givePermissionTo(Permission::all());
    }
}