<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
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

        $dateOfBirth = Carbon::createFromFormat('d/m/Y', '09/06/1981')->format('Y-m-d');

        User::create([
            'branchId' => $branchId,
            'name' => 'Lucio Vitorio',
            'email' => 'lucio@email.com',
            'password' => '12345',
            'cpf' => '09098059708',
            'dateOfBirth' => $dateOfBirth,
            'profile' => 'ADMIN',
        ]);

        User::create([
            'branchId' => $branchId,
            'name' => 'Barbara Bandeira',
            'email' => 'barbara@email.com',
            'password' => '12345',
            'cpf' => '12345678901',
            'dateOfBirth' => $dateOfBirth,
            'profile' => 'PASSADEIRA',
            'percent' => 10
        ]);
    }
}
