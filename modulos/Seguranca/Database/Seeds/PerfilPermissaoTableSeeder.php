<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Perfil;

class PerfilPermissaoTableSeeder extends Seeder
{
    public function run()
    {
        /** Perfil Administrador do Módulo Segurança */

        $perfil = Perfil::find(1); // Perfil administrador do modulo seguranca

        $perfil->permissoes()->attach([1]); // Permissão Index

        $perfil->permissoes()->attach([2, 3, 4, 5]); // Permissoes do recurso Módulo

        $perfil->permissoes()->attach([6, 7, 8, 9]); // Permissoes do recurso CategoriasRecursos

        $perfil->permissoes()->attach([10, 11, 12, 13]); // Permissoes do recurso Recursos

        $perfil->permissoes()->attach([14, 15, 16, 17]); // Permissoes do recurso Permissões

        $perfil->permissoes()->attach([18, 19, 20, 21, 22]); // Permissoes do recurso Perfis

        $perfil->permissoes()->attach([23, 24, 25, 26, 27, 28]); // Permissoes do recurso Usuários


        /** Perfil Administrador do Módulo Geral */
        $perfil = Perfil::find(2);

        $perfil->permissoes()->attach([29]); // Permissões Dashboard

        $perfil->permissoes()->attach([30, 31, 32, 33, 34]); //Permissões Pessoas

        $perfil->permissoes()->attach([35, 36, 37, 38]); // Permissoes do recurso Titulações

        $perfil->permissoes()->attach([39, 40, 41]); // Permissoes do recurso Titulações Informações

        $perfil->permissoes()->attach([42, 43, 44, 45]); // Permissoes do recurso Documentos

        /** Perfil Administrador do Módulo Acadêmico */

        $perfil = Perfil::find(3);

        $perfil->permissoes()->attach([46]); //Index Dashboard

        $perfil->permissoes()->attach([47, 48, 49, 50]); //Permissões do recurso polo

        $perfil->permissoes()->attach([51, 52, 53, 54]); // Permissoes do recurso Centros

        $perfil->permissoes()->attach([55, 56, 57, 58]); // Permissoes do recurso Departamentos

        $perfil->permissoes()->attach([59, 60, 61, 62]); // Permissoes do recurso Disciplinas

        $perfil->permissoes()->attach([63, 64, 65, 66]); // Permissoes do recurso Cursos

        $perfil->permissoes()->attach([67, 68, 69, 70]); // Permissoes do recurso Períodos Letivos

        $perfil->permissoes()->attach([71, 72]); // Permissoes do recurso Oferta de Cursos

        $perfil->permissoes()->attach([73, 74, 75, 76]); // Permissoes do recurso Alunos

        $perfil->permissoes()->attach([77, 78, 79, 80]); // Permissoes do recurso Professores

        $perfil->permissoes()->attach([81, 82, 83, 84]); // Permissoes do recurso Tutores

        $perfil->permissoes()->attach([85, 86, 87, 88]); // Permissoes do recurso Vinculos

        $perfil->permissoes()->attach([89, 90, 91]); // Permissoes do recurso Matricular Aluno no Curso

        $perfil->permissoes()->attach([92, 93]); // Permissoes do recurso Ofertar Disciplina

        $perfil->permissoes()->attach([94, 95]); // Permissoes do recurso Matricular Aluno na Disciplina

        $perfil->permissoes()->attach([96, 97, 98, 99, 100]); // Permissoes do recurso Lançamento de TCC

        $perfil->permissoes()->attach([101, 102]); // Permissoes do recurso Conclusão de Curso

        $perfil->permissoes()->attach([103, 104, 105]); // Permissoes do recurso Tutores Grupos

        $perfil->permissoes()->attach([106, 107, 108, 109, 110]); // Permissoes do recurso Matrizes Curriculares

        $perfil->permissoes()->attach([111, 112, 113, 114]); // Permissoes do recurso Grupos

        $perfil->permissoes()->attach([115, 116, 117, 118]); // Permissoes do recurso Turmas

        $perfil->permissoes()->attach([119, 120, 121, 122, 123]); // Permissoes do recurso Módulos Matrizes

        /** Perfil Administrador do Módulo Integração */

        $perfil = Perfil::find(4);

        $perfil->permissoes()->attach([124]); // Permissoes do recurso Dashboard

        $perfil->permissoes()->attach([125, 126, 127, 128, 129, 130]); // Permissoes do recurso Ambientes

        /** Perfil Administrador do Módulo de Monitoramento */

        $perfil = Perfil::find(5);

        $perfil->permissoes()->attach([131]); // Permissoes do recurso Dashboard

        $perfil->permissoes()->attach([132, 133]); // Permissoes do recurso Tempo Online
    }
}
