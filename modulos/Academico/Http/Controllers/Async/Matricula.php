<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Modulos\Academico\Events\AtualizarSituacaoMatriculaEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Events\AlterarStatusMatriculaEvent;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;

class Matricula extends BaseController
{
    protected $matriculaRepository;

    public function __construct(MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->matriculaRepository = $matriculaCursoRepository;
    }

    /**
     * Altera a situacao da matricula de um aluno.
     *
     * @param Request $request
     *
     * @return static
     *
     * @throws \Exception
     */
    public function postUpdateSituacao(Request $request)
    {
        try {
            $id = $request->input('id');
            $situacao = $request->input('situacao');

            $matricula = $this->matriculaRepository->find($id);

            $matricula->mat_situacao = $situacao;
            $matricula->save();

            event(new AtualizarSituacaoMatriculaEvent($matricula));

            return JsonResponse::create($matricula, JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return JsonResponse::create($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Busca todas as matriculas concluidas, de acordo com os parametros enviados na requisição
     *
     * @param Request $request
     * @return static
     */
    public function getMatriculasConcluidas(Request $request)
    {
        $parameters = $request->all();

        $parameters['mat_situacao'] = 'concluido';

        try {
            $matriculas = $this->matriculaRepository->findAll($parameters, null, ['pes_nome' => 'asc']);

            return JsonResponse::create($matriculas, 200);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return JsonResponse::create(['error' => $e->getMessage()], 500);
        }
    }
}
