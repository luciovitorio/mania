<?php

namespace Database\Seeders;

use App\Models\Rol;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class RolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entryDate1 = Carbon::now()->toDateString();
        $entryDate1Carbon = Carbon::parse($entryDate1);
        $oneWeekLater1 = $entryDate1Carbon->addWeek()->format('Y-m-d');

        $entryDate2 = Carbon::now()->toDateString();
        $entryDate2Carbon = Carbon::parse($entryDate2);
        $oneWeekLater2 = $entryDate2Carbon->addWeek()->format('Y-m-d');

        $clientId1 = DB::table('clients')
            ->where('name', 'Leonardo Henrique da Silva')
            ->first()
            ->id;

        $clientId2 = DB::table('clients')
            ->where('name', 'Lucia AVULSO')
            ->first()
            ->id;

        $userId = DB::table('users')
            ->where('name', 'Lucio Vitorio')
            ->first()
            ->id;

        Rol::create([
            'link' => '83067b4e-e067-4e94-875f-5b05881df294',
            'clientId' => $clientId1,
            'userId' => $userId,
            'created_at' => $entryDate1,
            'isHanger' => false,
            'status' => 'INICIO',
            'deliveryDate' => $oneWeekLater1,
            'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing.'
        ]);

        Rol::create([
            'link' => '3596ae1a-ea6f-4621-b448-d2a519767688',
            'clientId' => $clientId2,
            'userId' => $userId,
            'created_at' => $entryDate2,
            'isHanger' => false,
            'status' => 'INICIO',
            'deliveryDate' => $oneWeekLater2,
            'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing.'
        ]);
    }
}
