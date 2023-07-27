<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'name' => 'Loja A',
            'phone' => '3103-1368',
            'whatsapp' => '98729-9278',
            'email' => 'lojaA@email.com'
        ]);

        Branch::create([
            'name' => 'Loja B',
            'phone' => '1111-1111',
            'whatsapp' => '98729-9278',
            'email' => 'lojaB@email.com'
        ]);
    }
}
