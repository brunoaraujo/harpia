<?php

namespace Modulos\Geral\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Anexo;
use Modulos\Geral\Models\Documento;
use Carbon\Carbon;

class DocumentoRepository extends BaseRepository
{
    public function __construct(Documento $documento)
    {
        $this->model = $documento;
    }

    public function getCpfByPessoa($pessoaId)
    {
        return $this->model
                    ->join('gra_tipos_documentos', 'doc_tpd_id', 'tpd_id')
                    ->where('doc_pes_id', '=', $pessoaId)
                    ->where('tpd_nome', 'CPF')
                    ->get();
    }

    public function verifyCpf($cpf, $idPessoa = null)
    {
        $result = $this->model->where('doc_conteudo', $cpf)->where('doc_tpd_id', 2)->get();

        if (!$result->isEmpty()) {
            if (!is_null($idPessoa)) {
                $result = $result->where('doc_pes_id', $idPessoa);

                if (!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function updateDocumento(array $data, array $options)
    {
        $query = $this->model;

        foreach ($options as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        $registros = $query->get();

        if ($registros) {
            foreach ($registros as $obj) {
                $obj->fill($data);
                $obj->save();
            }

            return $registros->count();
        }

        return 0;
    }

    public function deleteDocumento($documentoId)
    {
        try {
            $anexoId = DB::table('gra_documentos')->where('doc_id', '=', $documentoId)->pluck('doc_anx_documento')->toArray();

            $this->update(['doc_anx_documento' => null], $documentoId);

            if ($anexoId) {
                $anexoRepository = new AnexoRepository(new Anexo());
                $result = $anexoRepository->deletarAnexo(array_pop($anexoId));
                return $result;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateOrCreate(array $attributes, array $data)
    {
        return $this->model->updateOrCreate($attributes, $data);
    }

    /**
     * Formata datas pt_BR para default MySQL
     * para update de registros
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = null)
    {
        if (!$attribute) {
            $attribute = $this->model->getKeyName();
        }

        $data['doc_data_expedicao'] = ($data['doc_data_expedicao'] != "") ?
            Carbon::createFromFormat('d/m/Y', $data['doc_data_expedicao'])->toDateString() :
            null;

        $registros = $this->model->where($attribute, '=', $id)->get();

        if ($registros) {
            foreach ($registros as $obj) {
                $obj->fill($data)->save();
            }

            return $registros->count();
        }

        return 0;
    }

    public function verifyTipoExists($tipodocumentoId, $pessoaId)
    {
        $tipo_exists = $this->model
            ->where('doc_pes_id', '=', $pessoaId)
            ->where('doc_tpd_id', '=', $tipodocumentoId)
            ->get();

        if ($tipo_exists->isEmpty()) {
            return true;
        }

        return false;
    }
}
