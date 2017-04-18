<?php
namespace Modulos\Geral\Database\Seeds\Development;

use Illuminate\Database\Seeder;

class GeralSeeder extends Seeder
{
    public function run()
    {
        $this->call(PessoaTableSeeder::class);
        $this->command->info('Pessoas Table seeded');
    }
}
