<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesasSeeder extends Seeder
{
    public function run(): void
    {
        for ($numero = 1; $numero <= 10; $numero++) {
            Mesa::updateOrCreate(['numero' => $numero], ['estado' => 'Disponible']);
        }
    }
}
