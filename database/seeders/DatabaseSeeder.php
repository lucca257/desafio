<?php

namespace Database\Seeders;

use App\Domain\carteira\Models\Carteira;
use App\Domain\Usuario\Models\Usuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Usuario::factory(5)->usuarioComum()->create();
        Usuario::factory(5)->create();
        Carteira::factory(10)->saldoMaximo()->create();
    }
}
