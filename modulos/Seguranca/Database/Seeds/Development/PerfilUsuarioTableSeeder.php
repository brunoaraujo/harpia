<?php

namespace Modulos\Seguranca\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Usuario;

class PerfilUsuarioTableSeeder extends Seeder
{
    public function run()
    {
        $usuario = Usuario::where('usr_usuario', 'admin@admin.com')->first(); // Usuario administrador
        $usuario->perfis()->attach([1, 2, 3, 4, 5]); // Atribui o perfil administrador do modulo seguranca para o usuario
    }
}
