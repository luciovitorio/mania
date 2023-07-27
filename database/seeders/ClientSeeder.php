<?php

namespace Database\Seeders;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dateOfBirth = Carbon::createFromFormat('d/m/Y', '06/06/1982')->format('Y-m-d');

        $branchId = DB::table('branches')
            ->where('name', 'Loja A')
            ->first()
            ->id;

        $planId01 = DB::table('plans')
            ->where('name', 'ECONOMICO 2022')
            ->first()
            ->id;

        $planId02 = DB::table('plans')
            ->where('name', 'Avulso')
            ->first()
            ->id;

        Client::create([
            'planId' => $planId01,
            'branchId' => $branchId,
            'name' => 'Leonardo Henrique da Silva',
            'cpf' => '09431641763',
            'rg' => '132482498',
            'dateOfBirth' => $dateOfBirth,
            'email' => 'michelle.oliveiraimenes@gmail.com',
            'homePhone' => '2121797952',
            'cellPhone' => '21982760000',
            'collectionFrequency' => 'SEMANAL',
            'collectionDay' => 'QUARTA',
            'collectionTime' => '09:00',
            'deliveryDay' => 'QUARTA',
            'deliveryTime' => '09:00',
            'collectionStart' => null,
            'description' => null,
            'deliveryAmount' => null,
            'dueDate' => 5,
            'isActive' => true
        ]);

        Client::create([
            'planId' => $planId02,
            'branchId' => $branchId,
            'name' => 'Lucia AVULSO',
            'collectionFrequency' => 'AVULSO',
            'deliveryAmount' => 10.00,
            'isActive' => true
        ]);
    }
}
