<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $seeder = [
        AdminUserSeeder::class,
        BrancheSeeder::class,
        PlansSeeder::class,
        ClothinsSeeder::class,
        ClientSeeder::class,
        RolsSeeder::class,
        RolsClothingSeeder::class,
        AddressSeeder::class
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(BranchSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(PlansSeeder::class);
        $this->call(ClothinsSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(RolsSeeder::class);
        $this->call(RolsClothingSeeder::class);
    }
}
