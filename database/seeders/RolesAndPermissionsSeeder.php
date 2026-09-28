<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Category
            ['name' => 'category.view', 'group' => 'category'],
            ['name' => 'category.create', 'group' => 'category'],
            ['name' => 'category.edit', 'group' => 'category'],
            ['name' => 'category.delete', 'group' => 'category'],

            // SubCategory
            ['name' => 'subcategory.view', 'group' => 'subcategory'],
            ['name' => 'subcategory.create', 'group' => 'subcategory'],
            ['name' => 'subcategory.edit', 'group' => 'subcategory'],
            ['name' => 'subcategory.delete', 'group' => 'subcategory'],

            // Brand
            ['name' => 'brand.view', 'group' => 'brand'],
            ['name' => 'brand.create', 'group' => 'brand'],
            ['name' => 'brand.edit', 'group' => 'brand'],
            ['name' => 'brand.delete', 'group' => 'brand'],

            // Store
            ['name' => 'store.view', 'group' => 'store'],
            ['name' => 'store.create', 'group' => 'store'],
            ['name' => 'store.edit', 'group' => 'store'],
            ['name' => 'store.delete', 'group' => 'store'],
            ['name' => 'store.approval', 'group' => 'store'],
            ['name' => 'store.status', 'group' => 'store'],

            // Product
            ['name' => 'product.view', 'group' => 'product'],
            ['name' => 'product.create', 'group' => 'product'],
            ['name' => 'product.edit', 'group' => 'product'],
            ['name' => 'product.delete', 'group' => 'product'],

            // Banner
            ['name' => 'banner.view', 'group' => 'banner'],
            ['name' => 'banner.create', 'group' => 'banner'],
            ['name' => 'banner.edit', 'group' => 'banner'],
            ['name' => 'banner.delete', 'group' => 'banner'],

            // Shipping Method
            ['name' => 'shipping.view', 'group' => 'shipping'],
            ['name' => 'shipping.create', 'group' => 'shipping'],
            ['name' => 'shipping.edit', 'group' => 'shipping'],
            ['name' => 'shipping.delete', 'group' => 'shipping'],

            // Payment Method
            ['name' => 'payment.view', 'group' => 'payment'],
            ['name' => 'payment.create', 'group' => 'payment'],
            ['name' => 'payment.edit', 'group' => 'payment'],
            ['name' => 'payment.delete', 'group' => 'payment'],
        ];

        foreach ($permissions as $perm) {
            $p = Permission::findOrCreate($perm['name'], 'web');
            $p->group = $perm['group'];
            $p->save();
        }

        // update cache to know about the newly created permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::findOrCreate('Super Admin', 'web');
        // $role->givePermissionTo(Permission::all());
    }
}