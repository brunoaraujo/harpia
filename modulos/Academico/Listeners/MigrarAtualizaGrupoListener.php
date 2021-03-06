<?php

namespace Modulos\Academico\Listeners;

use Harpia\Moodle\Moodle;
use Modulos\Academico\Events\AtualizarGrupoEvent;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarAtualizaGrupoListener
{
    protected $sincronizacaoRepository;
    protected $grupoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        GrupoRepository $grupoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->grupoRepository = $grupoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }
    
    public function handle(AtualizarGrupoEvent $event)
    {
        $gruposMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_grupos',
            'sym_status' => 1,
            'sym_action' => 'UPDATE'
        ]);

        if ($gruposMigrar->count()) {
            foreach ($gruposMigrar as $reg) {
                $grupo = $this->grupoRepository->find($reg->sym_table_id);

                // ambiente virtual vinculado à turma do grupo
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($grupo->grp_trm_id);

                if ($ambiente) {
                    $param = [];
                    
                    // url do ambiente
                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['functioname'] = 'local_integracao_update_group';
                    $param['action'] = 'UPDATE';

                    $param['data']['group']['grp_id'] = $grupo->grp_id;
                    $param['data']['group']['grp_nome'] = $grupo->grp_nome;

                    $moodleSync = new Moodle();

                    $retorno = $moodleSync->send($param);

                    $status = 3;

                    if (array_key_exists('status', $retorno)) {
                        if ($retorno['status'] == 'success') {
                            $status = 2;
                        }
                    }

                    event(new AtualizarSyncEvent($grupo, $status, $retorno['message'], 'UPDATE'));
                }
            }
        }
    }
}
