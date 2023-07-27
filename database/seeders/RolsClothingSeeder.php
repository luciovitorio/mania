<?php

namespace Database\Seeders;

use App\Models\rolClothin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolsClothingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolId1 = DB::table('rols')
            ->where('id', '83067b4e-e067-4e94-875f-5b05881df294')
            ->first()
            ->id;

        $rolId2 = DB::table('rols')
            ->where('id', '3596ae1a-ea6f-4621-b448-d2a519767688')
            ->first()
            ->id;

        $clothinId1 = DB::table('clothins')
            ->where('name', 'Bata Feminina')
            ->first()
            ->id;

        $clothinId2 = DB::table('clothins')
            ->where('name', 'Bermuda')
            ->first()
            ->id;

        $clothinId3 = DB::table('clothins')
            ->where('name', 'Blusa Manga Longa')
            ->first()
            ->id;

        $clothinId4 = DB::table('clothins')
            ->where('name', 'Camisa Social')
            ->first()
            ->id;

        rolClothin::create([
            'rolId' => $rolId1,
            'clothinId' => $clothinId1
        ]);

        rolClothin::create([
            'rolId' => $rolId1,
            'clothinId' => $clothinId2
        ]);

        rolClothin::create([
            'rolId' => $rolId2,
            'clothinId' => $clothinId3
        ]);

        rolClothin::create([
            'rolId' => $rolId2,
            'clothinId' => $clothinId4
        ]);
    }
}
