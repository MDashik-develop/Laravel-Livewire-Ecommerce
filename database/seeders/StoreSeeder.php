<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there is at least one user to own the store
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name'     => 'Store Owner',
                'email'    => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }

        // Create or update default approved store
        Store::updateOrCreate(
            ['id' => 1],
            [
                'user_id'     => $user->id,
                'name'        => 'Default Store',
                'slug'        => 'default-store',
                'description' => 'Welcome to our official store. We provide top-quality products with the best customer service.',
                'phone'       => '+8801700000000',
                'address'     => 'Dhaka, Bangladesh',
                'media_id'    => null,
                'is_approved' => true,
                'status'      => true,
            ]
        );
    }
}
