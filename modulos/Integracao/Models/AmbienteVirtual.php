<?php

namespace Modulos\Integracao\Models;

use Modulos\Core\Model\BaseModel;

class AmbienteVirtual extends BaseModel
{
    protected $table = 'int_ambientes_virtuais';

    protected $primaryKey = 'amb_id';

    protected $fillable = [
        'amb_nome',
        'amb_versao',
        'amb_url'
    ];

    protected $searchable = [
        'amb_nome' => 'like'
    ];

    public function servicos()
    {
        return $this->belongsToMany('Modulos\Integracao\Models\Servico', 'int_ambientes_servicos', 'asr_amb_id', 'asr_ser_id');
    }

    public function turmas()
    {
        return $this->belongsToMany('Modulos\Academico\Models\Turma', 'int_ambientes_turmas', 'atr_amb_id', 'atr_trm_id');
    }

    public function ambienteservico()
    {
        return $this->hasMany('Modulos\Integracao\Models\AmbienteServico', 'asr_amb_id', 'amb_id');
    }

    public function ambienteturma()
    {
        return $this->hasMany('Modulos\Integracao\Models\AmbienteTurma', 'atr_amb_id', 'amb_id');
    }
}
