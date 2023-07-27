<?php

namespace Database\Seeders;

use App\Models\Clothin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClothinsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branchId = DB::table('branches')
            ->where('name', 'Loja A')
            ->first()
            ->id;

        Clothin::create([
            'branchId' => $branchId,
            'name' => 'Bata Feminina',
            'type' => 'EASY',
        ]);

        Clothin::create([
            'branchId' => $branchId,
            'name' => 'Bermuda',
            'type' => 'EASY',
        ]);

        Clothin::create([
            'branchId' => $branchId,
            'name' => 'Blusa Manga Longa',
            'type' => 'EASY',
        ]);

        Clothin::create([
            'branchId' => $branchId,
            'name' => 'Camisa Social',
            'type' => 'HARD',
        ]);

        Clothin::create([
            'branchId' => $branchId,
            'name' => 'Lençol',
            'type' => 'HARD',
        ]);
    }
}
