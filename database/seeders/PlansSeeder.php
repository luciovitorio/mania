<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'ECONOMICO 2022',
            'pieceQuantity' => 20,
            'simplePieceQuantity' => 20,
            'difficultPieceQuantity' => 6,
            'simplePieceValue' => 3.45,
            'difficultPieceValue' => 5.45,
            'additionalSimplePieceValue' => 4.29,
            'additionalDifficultPieceValue' => 7.50,
            'isActive' => true
        ]);

        Plan::create([
            'name' => 'Avulso',
            'pieceQuantity' => 0,
            'simplePieceQuantity' => 0,
            'difficultPieceQuantity' => 0,
            'simplePieceValue' => 3.90,
            'difficultPieceValue' => 6.45,
            'additionalSimplePieceValue' => 3.90,
            'additionalDifficultPieceValue' => 6.70,
            'isActive' => true
        ]);

        Plan::create([
            'name' => 'Familiar 2022',
            'pieceQuantity' => 40,
            'simplePieceQuantity' => 40,
            'difficultPieceQuantity' => 12,
            'simplePieceValue' => 3.30,
            'difficultPieceValue' => 6.00,
            'additionalSimplePieceValue' => 3.90,
            'additionalDifficultPieceValue' => 6.90,
            'isActive' => true
        ]);

        Plan::create([
            'name' => 'Casal 2021',
            'pieceQuantity' => 30,
            'simplePieceQuantity' => 30,
            'difficultPieceQuantity' => 9,
            'simplePieceValue' => 3.00,
            'difficultPieceValue' => 5.15,
            'additionalSimplePieceValue' => 3.70,
            'additionalDifficultPieceValue' => 6.00,
            'isActive' => false
        ]);
    }
}
