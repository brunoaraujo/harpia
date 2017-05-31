<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;
use DB;
use stdClass;

class MatriculaCursoRepository extends BaseRepository
{
    protected $ofertaCursoRepository;
    protected $matrizCurricularRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $moduloMatrizRepository;
    protected $turmaRepository;
    protected $registroRepository;

    public function __construct(
        Matricula $matricula,
        OfertaCursoRepository $oferta,
        MatrizCurricularRepository $matriz,
        MatriculaOfertaDisciplinaRepository $matriculaOferta,
        ModuloMatrizRepository $modulo, TurmaRepository $turmaRepository,
        RegistroRepository $registroRepository
    ) {
        $this->model = $matricula;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaCursoRepository = $oferta;
        $this->matrizCurricularRepository = $matriz;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOferta;
        $this->moduloMatrizRepository = $modulo;
        $this->registroRepository = $registroRepository;
    }

    public function verifyIfExistsMatriculaByOfertaCursoOrTurma($alunoId, $ofertaCursoId, $turmaId)
    {
        $result = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->where('mat_alu_id', '=', $alunoId)
            ->where(function ($query) use ($turmaId, $ofertaCursoId) {
                $query->where('mat_trm_id', '=', $turmaId)
                    ->orWhere('trm_ofc_id', '=', $ofertaCursoId);
            })->get();


        if ($result->count()) {
            return true;
        }

        return false;
    }

    public function verifyIfExistsMatriculaByCursoAndSituacao($alunoId, $cursoId)
    {
        $result = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->where('mat_alu_id', '=', $alunoId)
            ->whereNotIn('mat_situacao', ['concluido', 'evadido', 'desistente'])
            ->where('ofc_crs_id', '=', $cursoId)
            ->get();

        if ($result->count()) {
            return true;
        }

        return false;
    }

    public function verifyIfExistsMatriculaInCursoGraducao($alunoId)
    {
        $result = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id')->where('crs_nvc_id', '=', '3');
            })
            ->where('mat_alu_id', '=', $alunoId)
            ->whereNotIn('mat_situacao', ['concluido', 'evadido', 'desistente'])
            ->get();

        if ($result->count()) {
            return true;
        }

        return false;
    }

    public function verifyExistsVagasByTurma($turmaId)
    {
        $result = $this->model
                        ->rightJoin('acd_turmas', function ($join) {
                            $join->on('mat_trm_id', '=', 'trm_id');
                        })
                        ->select('trm_qtd_vagas', DB::raw('COUNT(mat_trm_id) as qtd_matriculas'))
                        ->where('trm_id', '=', $turmaId)
                        ->groupBy('trm_id')
                        ->first();

        if ($result) {
            if ($result->qtd_matriculas < $result->trm_qtd_vagas) {
                return true;
            }
        }

        return false;
    }

    public function listsCursosNotMatriculadoByAluno($alunoId)
    {
        $cursosMatriculados = $this->model
                                    ->join('acd_turmas', function ($join) {
                                        $join->on('mat_trm_id', '=', 'trm_id');
                                    })
                                    ->join('acd_ofertas_cursos', function ($join) {
                                        $join->on('trm_ofc_id', '=', 'ofc_id');
                                    })
                                    ->join('acd_cursos', function ($join) {
                                        $join->on('ofc_crs_id', '=', 'crs_id');
                                    })
                                    ->select('crs_id')
                                    ->where('mat_alu_id', '=', $alunoId)
                                    ->whereIn('mat_situacao', ['cursando'])
                                    ->pluck('crs_id')
                                    ->toArray();

        $query = DB::table('acd_cursos')
                    ->whereNotIn('crs_id', $cursosMatriculados);

        if ($this->verifyIfExistsMatriculaInCursoGraducao($alunoId)) {
            $query = $query->where('crs_nvc_id', '<>', 3);
        }

        return $query->pluck('crs_nome', 'crs_id');
    }

    public function findAll(array $options, array $select = null, array $order = null)
    {
        $query = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })
            ->leftJoin('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->leftJoin('acd_grupos', function ($join) {
                $join->on('mat_grp_id', '=', 'grp_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            });

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }
        }

        if (!is_null($select)) {
            $query = $query->select($select);
        }

        if (!is_null($order)) {
            foreach ($order as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }

        return $query->get();
    }

    public function createMatricula($alunoId, array $options)
    {
        // verifica se aluno possui matricula na oferta de curso ou na turma
        if ($this->verifyIfExistsMatriculaByOfertaCursoOrTurma($alunoId, $options['ofc_id'], $options['mat_trm_id'])) {
            return array(
                'type' => 'error',
                'message' => 'Aluno já possui matricula na oferta ou turma'
            );
        }

        // verifica se aluno possui matricula ativa no curso, mesmo sendo em ofertas diferentes, contanto que tenha concluido, evadido
        // ou abandonado o curso
        if ($this->verifyIfExistsMatriculaByCursoAndSituacao($alunoId, $options['crs_id'])) {
            return array(
                'type' => 'error',
                'message' => 'Aluno já possui matricula ativa no curso selecionado'
            );
        }

        // Verifica o nivel do curso, e caso seja de GRADUACAO, verifica se o aluno possui matrícula em algum curso de graduacao
        $curso = DB::table('acd_cursos')->where('crs_id', $options['crs_id'])->first();

        // caso seja de Graducao
        if ($curso->crs_nvc_id == 3) {
            if ($this->verifyIfExistsMatriculaInCursoGraducao($alunoId)) {
                return array(
                    'type' => 'error',
                    'message' => 'Aluno já possui matricula ativa em outro curso de graduação'
                );
            }
        }

        // verifica se a turma ainda possui vagas disponiveis
        if (!$this->verifyExistsVagasByTurma($options['mat_trm_id'])) {
            return array(
                'type' => 'error',
                'message' => 'A turma escolhida não possui mais vagas disponiveis'
            );
        }

        $dataMatricula = [
            'mat_alu_id' => $alunoId,
            'mat_trm_id' => $options['mat_trm_id'],
            'mat_pol_id' => $options['mat_pol_id'],
            'mat_grp_id' => ($options['mat_grp_id'] == '') ? null : $options['mat_grp_id'],
            'mat_modo_entrada' => $options['mat_modo_entrada'],
            'mat_situacao' => 'cursando'
        ];

        $matricula = $this->create($dataMatricula);
        if ($matricula) {
            return array(
                'type' => 'success',
                'message' => 'Matricula efetuada com sucesso!',
                'matricula' => $matricula
            );
        }

        return array(
            'type' => 'error',
            'message' => 'Erro ao tentar matricular aluno'
        );
    }

    public function findMatriculaIdByTurmaAluno($alunoId, $turmaId)
    {
        $matriculadoemtcc = null;

        $matricula = DB::table('acd_matriculas')
            ->where('mat_trm_id', '=', $turmaId)
            ->where('mat_alu_id', '=', $alunoId)
            ->first();

        if ($matricula != null) {
            $matriculadoemtcc = DB::table('acd_modulos_disciplinas')
                ->join('acd_ofertas_disciplinas', 'ofd_mdc_id', '=', 'mdc_id')
                ->join('acd_matriculas_ofertas_disciplinas', 'mof_ofd_id', '=', 'ofd_id')
                ->where('mdc_tipo_disciplina', '=', 'tcc')
                ->where('mof_mat_id', '=', $matricula->mat_id)->first();
        }

        if ($matriculadoemtcc === null) {
            return null;
        }

        return $matricula;
    }

    public function findDadosByTurmaId($turmaId)
    {
        $dados = DB::table('acd_matriculas_ofertas_disciplinas')
            ->join('acd_ofertas_disciplinas', function ($join) {
                $join->on('mof_ofd_id', '=', 'ofd_id');
            })
            ->join('acd_modulos_disciplinas', function ($join) {
                $join->on('ofd_mdc_id', '=', 'mdc_id');
            })
            ->join('acd_matriculas', function ($join) {
                $join->on('mof_mat_id', '=', 'mat_id');
            })
            ->join('acd_alunos', 'mat_alu_id', '=', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', '=', 'pes_id')
            ->leftJoin('acd_lancamentos_tccs', 'ltc_mof_id', '=', 'mof_id')
            ->where('mdc_tipo_disciplina', '=', 'tcc')
            ->where('mat_trm_id', '=', $turmaId)
            ->orderBy('pes_nome', 'asc')
            ->get();


        return $dados;
    }

    public function verifyIfAlunoAprovadoLancadoTcc($matriculaId)
    {
        $result = $this->model
            ->join('acd_matriculas_ofertas_disciplinas', 'mof_mat_id', 'mat_id')
            ->join('acd_ofertas_disciplinas', 'mof_ofd_id', 'ofd_id')
            ->join('acd_modulos_disciplinas', 'ofd_mdc_id', 'mdc_id')
            ->leftJoin('acd_lancamentos_tccs', 'ltc_mof_id', '=', 'mof_id')
            ->where('mdc_tipo_disciplina', '=', 'tcc')
            ->where('mof_mat_id', '=', $matriculaId)
            ->whereIn('mof_situacao_matricula', ['aprovado_media', 'aprovado_final'])
            ->whereNotNull('ltc_id')
            ->first();

        if (!is_null($result)) {
            return true;
        }

        return false;
    }

    public function verifyIfAlunoIsAptoOrNot($matriculaId, $ofertaCursoId)
    {
        // busca as informacoes da oferta de curso
        $ofertaCurso = $this->ofertaCursoRepository->find($ofertaCursoId);

        // busca as informacoes da matriz curricular do curso
        $matrizCurricular = $this->matrizCurricularRepository->find($ofertaCurso->ofc_mtc_id);

        //busca os modulos da matriz
        $modulos = $this->moduloMatrizRepository->getAllModulosByMatriz($matrizCurricular->mtc_id);

        // busca todas as disciplinas da matriz do curso
        $disciplinasMatriz = $this->matrizCurricularRepository->getDisciplinasByMatrizId($matrizCurricular->mtc_id)
            ->pluck('mdc_id')->toArray();

        // busca as informações da matricula
        $matricula = $this->find($matriculaId);

        if ($matricula->mat_situacao == 'concluido') {
            return 2;
        }

        if ($matricula->mat_situacao == 'cursando') {
            $quantDisciplinasObrigatorias = 0;
            $quantDisciplinasObrigatoriasAprovadas = 0;

            foreach ($modulos as $modulo) {
                $disciplinasAluno = $this->matriculaOfertaDisciplinaRepository->getAllMatriculasByAlunoModuloMatriz($matricula->mat_alu_id, $modulo->mdo_id);

                $cargaHorariaEletivas = 0;
                $creditosEletivas = 0;

                foreach ($disciplinasAluno as $disciplina) {
                    if ($disciplina->mdc_tipo_disciplina == 'obrigatoria') {
                        $quantDisciplinasObrigatorias++;
                    }
                    // Verifica se a oferta de disciplina está na matriz do curso
                    if (in_array($disciplina->mdc_id, $disciplinasMatriz)) {
                        // Caso o aluno foi aprovado na disciplina, incrementa a variavel
                        if (in_array($disciplina->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                            if ($disciplina->mdc_tipo_disciplina == 'obrigatoria') {
                                $quantDisciplinasObrigatoriasAprovadas++;
                            }

                            if ($disciplina->mdc_tipo_disciplina == 'eletiva') {
                                $cargaHorariaEletivas += $disciplina->dis_carga_horaria;
                                $creditosEletivas += $disciplina->dis_creditos;
                            }
                        }
                    }
                }

                // se o aluno não atingir a carga horaria minima de disciplinas eletivas do módulo, não está apto para conclusão
                if ((!is_null($modulo->mdo_cargahoraria_min_eletivas)) && ($cargaHorariaEletivas < $modulo->mdo_cargahoraria_min_eletivas)) {
                    return 0;
                }

                // se o aluno não atingir os creditos minimos de disciplinas eletivas do módulo, não está apto para conclusão
                if ((!is_null($modulo->mdo_creditos_min_eletivas)) && ($creditosEletivas < $modulo->mdo_creditos_min_eletivas)) {
                    return 0;
                }
            }

            $temTcc = false;
            // Verifica se a matriz possui disciplina tcc
            if ($this->matrizCurricularRepository->verifyIfExistsDisciplinaTccInMatriz($matrizCurricular->mtc_id)) {
                // verifica se o aluno foi aprovado e possui Tcc lançado
                if ($this->verifyIfAlunoAprovadoLancadoTcc($matricula->mat_id)) {
                    $temTcc = true;
                }
            }

            // se o curso for de nivel Tecnico, não possui tcc, mas seta a variavel true
            if ($ofertaCurso->curso->crs_nvc_id == 1) {
                $temTcc = true;
            }

            // Casos de situações
            if (($quantDisciplinasObrigatoriasAprovadas == $quantDisciplinasObrigatorias) && $temTcc) {
                return 1;
            }
        }

        return 0;
    }

    public function getAlunosAptosOrNot($ofertaCursoId, $turmaId, $poloId)
    {
        // busca todas as matriculas da turma
        $matriculas = $this->findAll(['mat_trm_id' => $turmaId, 'mat_pol_id' => $poloId], null, ['pes_nome' => 'asc']);

        $result = [];
        if ($matriculas->count()) {
            foreach ($matriculas as $matricula) {
                $obj = new StdClass;

                $obj->mat_id = $matricula->mat_id;
                $obj->alu_id = $matricula->alu_id;
                $obj->pes_nome = $matricula->pes_nome;
                $obj->status = 0;
                $obj->data_conclusao = ' --- ';

                if ($matricula->mat_situacao == 'concluido') {
                    $obj->status = 2;
                    $obj->data_conclusao = $matricula->mat_data_conclusao;
                    $result[] = $obj;
                    continue;
                }

                if ($matricula->mat_situacao == 'cursando') {
                    $obj->status = $this->verifyIfAlunoIsAptoOrNot($matricula->mat_id, $ofertaCursoId);
                }

                $result[] = $obj;
            }
        }

        return $result;
    }

    public function getAlunosAptosCertificacao($turmaId, $moduloId)
    {
        // busca todas as matriculas da turma
        $matriculas = $this->findAll(['mat_trm_id' => $turmaId], null, ['pes_nome' => 'asc']);

        $aptos = [];
        $certificados = [];

        if (!$matriculas->count()) {
            return array('aptos' => $aptos, 'certificados' => $certificados);
        }

        foreach ($matriculas as $matricula) {

            // Checar se aluno concluiu todas as disciplinas
            $turma = $this->turmaRepository->find($turmaId);
            $ofertaCurso = $this->ofertaCursoRepository->find($turma->trm_ofc_id);

            /**
             * Se aluno concluiu todas as disciplinas, nao estah apto para certificacao
             */
            if (!$this->verifyIfAlunoIsAptoOrNot($matricula->mat_id, $ofertaCurso->ofc_id)) {
                continue;
            }

            // Verifica se o aluno esta apto para certificacao
            if ($this->verifyIfAlunoIsAptoCertificacao($matricula->mat_id, $turmaId, $moduloId)) {
                if ($this->registroRepository->matriculaTemRegistro($matricula->mat_id, $moduloId)) {
                    $certificados[] = $matricula;
                    continue;
                }

                $aptos[] = $matricula;
            }
        }

        return array('aptos' => $aptos, 'certificados' => $certificados);
    }

    public function verifyIfAlunoIsAptoCertificacao($matriculaId, $turmaId, $moduloId)
    {
        $apto = false;

        // busca as informacoes da oferta de curso
        $turma = $this->turmaRepository->find($turmaId);
        $ofertaCurso = $this->ofertaCursoRepository->find($turma->trm_ofc_id);

        // busca as informacoes da matriz curricular do curso
        $matrizCurricular = $this->matrizCurricularRepository->find($ofertaCurso->ofc_mtc_id);

        // busca os modulos da matriz
        $modulos = $this->moduloMatrizRepository->getAllModulosByMatriz($matrizCurricular->mtc_id);

        // busca todas as disciplinas da matriz do curso
        $disciplinasMatriz = $this->matrizCurricularRepository->getDisciplinasByMatrizId($matrizCurricular->mtc_id)
            ->pluck('mdc_id')->toArray();

        // busca as informações da matricula
        $matricula = $this->find($matriculaId);

        if ($matricula->mat_situacao == 'concluido') {
            return false;
        }

        if ($matricula->mat_situacao == 'cursando') {
            $quantDisciplinasObrigatorias = 0;
            $quantDisciplinasObrigatoriasAprovadas = 0;

            foreach ($modulos as $modulo) {
                if ($modulo->mdo_id > $moduloId) {
                    break;
                }

                $disciplinasAluno = $this->matriculaOfertaDisciplinaRepository->getAllMatriculasByAlunoModuloMatriz($matricula->mat_alu_id, $modulo->mdo_id);

                if (!$disciplinasAluno->count()) {
                    return false;
                }

                $cargaHorariaEletivas = 0;
                $creditosEletivas = 0;

                foreach ($disciplinasAluno as $disciplina) {
                    if ($disciplina->mdc_tipo_disciplina == 'obrigatoria') {
                        $quantDisciplinasObrigatorias++;
                    }
                    // Verifica se a oferta de disciplina está na matriz do curso
                    if (in_array($disciplina->mdc_id, $disciplinasMatriz)) {
                        // Caso o aluno foi aprovado na disciplina, incrementa a variavel
                        if (in_array($disciplina->mof_situacao_matricula, ['aprovado_media', 'aprovado_final'])) {
                            if ($disciplina->mdc_tipo_disciplina == 'obrigatoria') {
                                $quantDisciplinasObrigatoriasAprovadas++;
                            }

                            if ($disciplina->mdc_tipo_disciplina == 'eletiva') {
                                $cargaHorariaEletivas += $disciplina->dis_carga_horaria;
                                $creditosEletivas += $disciplina->dis_creditos;
                            }
                        }
                    }
                }

                // se o aluno não atingir a carga horaria minima de disciplinas eletivas do módulo, não está apto para conclusão
                if ((!is_null($modulo->mdo_cargahoraria_min_eletivas)) && ($cargaHorariaEletivas < $modulo->mdo_cargahoraria_min_eletivas)) {
                    return false;
                }

                // se o aluno não atingir os creditos minimos de disciplinas eletivas do módulo, não está apto para conclusão
                if ((!is_null($modulo->mdo_creditos_min_eletivas)) && ($creditosEletivas < $modulo->mdo_creditos_min_eletivas)) {
                    return false;
                }
            }

            // Casos de situações
            if (($quantDisciplinasObrigatoriasAprovadas == $quantDisciplinasObrigatorias)) {
                return true;
            }
        }

        return false;
    }

    public function concluirMatricula($matriculaId, $ofertaCursoId)
    {
        // verifica se matricula existe
        $matricula = $this->find($matriculaId);

        if ($matricula) {
            // verifica se matricula está apta para conclusao
            $result = $this->verifyIfAlunoIsAptoOrNot($matriculaId, $ofertaCursoId);

            if ($result == 1) {
                $matricula->mat_situacao = 'concluido';
                $matricula->mat_data_conclusao = date('d/m/Y');
                $matricula->save();

                return $matricula;
            }
        }

        return false;
    }

    public function paginateRequestByOfertaCurso(array $requestParameters = null)
    {
        $sort = array();
        if (empty($requestParameters)) {
            return new Collection();
        }

        if (empty($requestParameters['trm_id'])) {
            return new Collection();
        }

        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            $query = $this->model
                ->join('acd_turmas', function ($join) {
                    $join->on('mat_trm_id', '=', 'trm_id');
                })
                ->join('acd_ofertas_cursos', function ($join) {
                    $join->on('trm_ofc_id', '=', 'ofc_id');
                })
                ->join('acd_cursos', function ($join) {
                    $join->on('ofc_crs_id', '=', 'crs_id');
                })
                ->leftJoin('acd_polos', function ($join) {
                    $join->on('mat_pol_id', '=', 'pol_id');
                })
                ->leftJoin('acd_grupos', function ($join) {
                    $join->on('mat_grp_id', '=', 'grp_id');
                })
                ->join('acd_alunos', function ($join) {
                    $join->on('mat_alu_id', '=', 'alu_id');
                })->join('gra_pessoas', function ($join) {
                    $join->on('alu_pes_id', '=', 'pes_id');
                })
                ->select('mat_id', 'pes_nome', 'mat_situacao', 'trm_nome', 'pol_nome', 'pes_email')
                ->where('mat_trm_id', $requestParameters['trm_id'])
                ->orderBy($sort['field'], $sort['sort']);

            if ($requestParameters['mat_situacao'] != null) {
                $query = $query->where('mat_situacao', $requestParameters['mat_situacao']);
            }

            return $query->paginate(15);
        }

        $dados = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })
            ->leftJoin('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->leftJoin('acd_grupos', function ($join) {
                $join->on('mat_grp_id', '=', 'grp_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })
            ->select('mat_id', 'pes_nome', 'mat_situacao', 'trm_nome', 'pol_nome', 'pes_email')
            ->where('mat_trm_id', $requestParameters['trm_id']);

        if ($requestParameters['mat_situacao'] != null) {
            $dados = $dados->where('mat_situacao', $requestParameters['mat_situacao']);
        }

        return $dados->paginate(15);
    }

    public function findAllBySitucao(array $requestParameters)
    {
        $query = $this->model
            ->join('acd_turmas', function ($join) {
                $join->on('mat_trm_id', '=', 'trm_id');
            })
            ->join('acd_ofertas_cursos', function ($join) {
                $join->on('trm_ofc_id', '=', 'ofc_id');
            })
            ->join('acd_cursos', function ($join) {
                $join->on('ofc_crs_id', '=', 'crs_id');
            })
            ->leftJoin('acd_polos', function ($join) {
                $join->on('mat_pol_id', '=', 'pol_id');
            })
            ->leftJoin('acd_grupos', function ($join) {
                $join->on('mat_grp_id', '=', 'grp_id');
            })
            ->join('acd_alunos', function ($join) {
                $join->on('mat_alu_id', '=', 'alu_id');
            })->join('gra_pessoas', function ($join) {
                $join->on('alu_pes_id', '=', 'pes_id');
            })
        ->select('mat_id', 'pes_nome', 'mat_situacao', 'trm_nome', 'pol_nome', 'pes_email')
        ->where('mat_trm_id', $requestParameters['trm_id'])
        ->orderBy('pes_nome', 'asc');

        if ($requestParameters['mat_situacao'] != null) {
            $query = $query->where('mat_situacao', $requestParameters['mat_situacao']);
        }

        return $query->get();
    }

    public function getPrintData($IdMatricula, $IdModulo)
    {

        //recebe livro, folha e registro da certificação
        $livfolreg = DB::table('acd_registros')
                  ->where('reg_mat_id', $IdMatricula)
                  ->where('reg_mdo_id', $IdModulo)
                  ->join('acd_livros', 'reg_liv_id', 'liv_id')
                  ->first();

        if (!$livfolreg) {
            return null;
        }
        $matricula = $this->model->find($IdMatricula);
        $curso = $matricula->turma->ofertacurso->curso;
        $pessoa = $matricula->aluno->pessoa;

        $modulo = DB::table('acd_modulos_matrizes')
                  ->where('mdo_id', $IdModulo)->first();

        $ofertasdisciplinas = DB::table('acd_ofertas_disciplinas')
        ->join('acd_matriculas_ofertas_disciplinas', 'mof_ofd_id', 'ofd_id')
        ->join('acd_modulos_disciplinas', 'ofd_mdc_id', 'mdc_id')
        ->join('acd_disciplinas', 'mdc_dis_id', 'dis_id')
        ->where('mdc_mdo_id', $IdModulo)
        ->where('mof_mat_id', $IdMatricula)
        ->get();

        $cargahoraria = 0;
        $disciplinas = [];
        $numerador = 0;

        foreach ($ofertasdisciplinas as $modulodisciplina) {
            $disciplinas[] = $modulodisciplina->dis_nome;
            $cargahoraria = $cargahoraria + $modulodisciplina->dis_carga_horaria;
            $numerador = $modulodisciplina->mof_mediafinal*$modulodisciplina->dis_carga_horaria + $numerador;
        }

        //formata o coeficiente do módulo
        $coeficiente = $numerador/$cargahoraria;
        $coeficiente = number_format($coeficiente, 2, '.', '');

        $descricaomodulo = $modulo->mdo_descricao;
        $qualificacaomodulo = $modulo->mdo_qualificacao;

        $query = DB::table('gra_documentos')
                  ->where('doc_pes_id', $pessoa->pes_id)
                  ->where('doc_tpd_id', 2)
                  ->first();
        $nomepessoa = $pessoa->pes_nome;

        //formatação do CPF de pessoa
        $cpfpessoa = $query->doc_conteudo;
        $parte_um     = substr($cpfpessoa, 0, 3);
        $parte_dois   = substr($cpfpessoa, 3, 3);
        $parte_tres   = substr($cpfpessoa, 6, 3);
        $parte_quatro = substr($cpfpessoa, 9, 2);
        $cpfpessoaformatado = "$parte_um.$parte_dois.$parte_tres-$parte_quatro";

        //prepara o array de retorno
        $returnData = [
                        'DESCRICAOMODULO' => mb_strtoupper($descricaomodulo, "UTF-8"),
                        'QUALIFICACAOMODULO' => mb_strtoupper($qualificacaomodulo, "UTF-8") ,
                        'CARGAHORARIAMODULO' => $cargahoraria,
                        'DISCIPLINAS' => $disciplinas,
                        'EIXOCURSO' => $curso->crs_eixo ,
                        'LIVRO' => $livfolreg->liv_numero,
                        'FOLHA' => $livfolreg->reg_folha,
                        'REGISTRO'=> $livfolreg->reg_registro,
                        'COEFICIENTEDOMODULO'=> $coeficiente,
                        'PESSOANOME'=> mb_strtoupper($nomepessoa, "UTF-8"),
                        'PESSOACPF'=> $cpfpessoaformatado
                        ];
        return $returnData;
    }
}
