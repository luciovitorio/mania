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

        $clothinData = [
            [
                'name' => 'Bata Feminina',
                'type' => 'EASY',
            ],
            [
                'name' => 'Bermuda',
                'type' => 'EASY',
            ],
            [
                'name' => 'Blusa Manga Longa',
                'type' => 'EASY',
            ],
            [
                'name' => 'Calça',
                'type' => 'EASY',
            ],
            [
                'name' => 'Camiseta',
                'type' => 'EASY',
            ],
            [
                'name' => 'Camisa Polo',
                'type' => 'EASY',
            ],
            [
                'name' => 'Camisola',
                'type' => 'EASY',
            ],
            [
                'name' => 'Fronhas',
                'type' => 'EASY',
            ],
            [
                'name' => 'Pano de Prato',
                'type' => 'EASY',
            ],
            [
                'name' => 'Peça de Bebê',
                'type' => 'EASY',
            ],
            [
                'name' => 'Regata',
                'type' => 'EASY',
            ],
            [
                'name' => 'Saia',
                'type' => 'EASY',
            ],
            [
                'name' => 'Toalha',
                'type' => 'EASY',
            ],
            [
                'name' => 'Toalha de Mesa',
                'type' => 'EASY',
            ],
            [
                'name' => 'Vestido',
                'type' => 'EASY',
            ],
            [
                'name' => 'Camisa Social',
                'type' => 'HARD',
            ],
            [
                'name' => 'Lençol',
                'type' => 'HARD',
            ],
        ];

        // Loop para criar os registros
        foreach ($clothinData as $data) {
            Clothin::create(array_merge($data, ['branchId' => $branchId]));
        }
    }
}
