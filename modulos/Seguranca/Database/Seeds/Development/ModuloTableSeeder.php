<?php

namespace Modulos\Seguranca\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Modulo;

class ModuloTableSeeder extends Seeder
{
    public function run()
    {
        //Modulo Segurança
        $modulo = new Modulo; // id = 1
        $modulo->mod_rota = 'seguranca';
        $modulo->mod_nome = 'Segurança';
        $modulo->mod_descricao = 'Módulo de gerenciamento de permissões de acesso do usuário';
        $modulo->mod_icone = 'fa fa-lock';
        $modulo->mod_class = 'bg-red';
        $modulo->mod_ativo = 1;
        $modulo->save();

        //Modulo Geral
        $modulo = new Modulo;  // id = 2
        $modulo->mod_rota = 'geral';
        $modulo->mod_nome = 'Geral';
        $modulo->mod_descricao = 'Módulo de cadastro Geral';
        $modulo->mod_icone = 'fa fa-cubes';
        $modulo->mod_class = 'bg-blue';
        $modulo->mod_ativo = 1;
        $modulo->save();

        //Modulo Acadêmico
        $modulo = new Modulo; // id = 3
        $modulo->mod_rota = 'academico';
        $modulo->mod_nome = 'Acadêmico';
        $modulo->mod_descricao = 'Módulo de cadastro Acadêmico';
        $modulo->mod_icone = 'fa fa-university';
        $modulo->mod_class = 'bg-green';
        $modulo->mod_ativo = 1;
        $modulo->save();

        //Modulo Integração
        $modulo = new Modulo; // id = 4
        $modulo->mod_rota = 'integracao';
        $modulo->mod_nome = 'Integração';
        $modulo->mod_descricao = 'Módulo de Integração';
        $modulo->mod_icone = 'fa fa-cogs';
        $modulo->mod_class = 'bg-aqua';
        $modulo->mod_ativo = 1;
        $modulo->save();

        //Modulo monitoramento
        $modulo = new Modulo; // id = 5
        $modulo->mod_rota = 'monitoramento';
        $modulo->mod_nome = 'Monitoramento';
        $modulo->mod_descricao = 'Módulo de Monitoramento';
        $modulo->mod_icone = 'fa fa-line-chart';
        $modulo->mod_class = 'bg-yellow';
        $modulo->mod_ativo = 1;
        $modulo->save();
    }
}
