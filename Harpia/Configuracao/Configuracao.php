<?php

namespace Harpia\Configuracao;

use Modulos\Geral\Repositories\ConfiguracaoRepository;

class Configuracao
{
    private $configuracaoRepository;

    public function __construct(ConfiguracaoRepository $configuracaoRepository)
    {
        $this->configuracaoRepository = $configuracaoRepository;
    }

    /**
     * Retorna o valor de uma configuração dada
     * @param $config
     * @return mixed|null
     */
    public function get($config)
    {
        return $this->configuracaoRepository->getByName($config);
    }

    /**
     * Cria ou atualiza uma configuração
     * @param $config
     * @param $valor
     * @param $modulo
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Exception
     */
    public function set($config, $valor, $modulo)
    {
        $configData = [
            'cnf_mod_id' => $modulo,
            'cnf_nome' => $config,
            'cnf_valor' => (string)$valor
        ];

        try {
            if ($this->configExists($config)) {
                $this->configuracaoRepository->update($configData);
                return;
            }

            $this->configuracaoRepository->create($configData);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return redirect()->back();
        }
    }

    /**
     * Retorna todas as configuracoes com os modulos correspondentes
     * @return mixed
     */
    public function getAll()
    {
        return $this->configuracaoRepository->getAll();
    }

    /**
     * Remove uma dada configuracao da tabela
     * @param $config
     * @return int
     */
    public function remove($config)
    {
        return $this->configuracaoRepository->delete($config);
    }

    /**
     * Verifica se existe uma dada configuracao no banco
     * @param $config
     * @return bool
     */
    private function configExists($config)
    {
        return $this->configuracaoRepository->configExists($config);
    }
}
