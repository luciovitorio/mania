<?php

namespace Database\Seeders;

use App\Models\System_Info;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        System_Info::create([
            'store_name' => 'Mania',
            'whatsapp_business_id' => '116520024866009',
            'phone_number_id' => '117063934811059',
            'phone_number' => '980376882',
            'facebook_token' => 'EABaqnZBqFKBEBO7vnHDZBOmOtBicfKthIE8j7NXDSWMQNZBZCHYj5DSLzWjccG1t1DpAGJHDCAJ0bLpliKNjOcpqTeilXS7s3QZCGFcqUYijNNm36cpqYEegtOqyIxMaKQZBZCGvzLQg8KPBxJYsX5XRpVZAef2ZC0ZAkiOeZBMSZC8GFXfWLlPDZCEpj0rHeee9ZB27rqCI2SR1zwAZBqYmXVdE4MLuHKQyTIzKNxm',
        ]);
    }
}
