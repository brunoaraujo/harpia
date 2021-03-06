<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\ModuloMatriz;
use DB;

class ModuloMatrizRepository extends BaseRepository
{
    public function __construct(ModuloMatriz $modulomatriz)
    {
        $this->model = $modulomatriz;
    }

    public function paginateRequestByMatriz($matrizId, array $requestParameters = null)
    {
        $sort = array();
        if (!empty($requestParameters['field']) and !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];
            return $this->model->where('mdo_mtc_id', '=', $matrizId)
                ->orderBy($sort['field'], $sort['sort'])
                ->paginate(15);
        }
        return $this->model->where('mdo_mtc_id', '=', $matrizId)->paginate(15);
    }

    public function verifyNameMatriz($moduloName, $idMatriz, $moduloId = null)
    {
        $result = $this->model->where('mdo_nome', $moduloName)->where('mdo_mtc_id', $idMatriz)->get();

        if (!$result->isEmpty()) {
            if (!is_null($moduloId)) {
                $result = $result->where('mdo_id', $moduloId);

                if (!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function getAllModulosByMatriz($matrizId)
    {
        return $this->model->join('acd_matrizes_curriculares', function ($join) {
            $join->on('mdo_mtc_id', '=', 'mtc_id');
        })->where('mdo_mtc_id', '=', $matrizId)->get();
    }

    public function buscar($matriz, $nome)
    {
        $result = $this->model
                        ->where('mdo_mtc_id', '=', $matriz)
                        ->leftJoin('acd_modulos_disciplinas', 'mdc_mdo_id', '=', 'mdo_id')
                        ->leftJoin('acd_disciplinas', 'dis_id', '=', 'mdc_dis_id')
                        ->join('acd_niveis_cursos', 'nvc_id', '=', 'dis_nvc_id')
                        ->where('dis_nome', 'like', "%$nome%")->get();


        if ($result) {
            return $result;
        }

        return null;
    }
}
