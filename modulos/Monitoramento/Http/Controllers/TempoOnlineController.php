<?php

namespace Modulos\Monitoramento\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use App\Http\Controllers\Controller;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Configuracao;

/**
 * Class IndexController.
 */
class TempoOnlineController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    protected $cursoRepository;
    protected $ambientevirtualRepository;

    public function __construct(CursoRepository $cursoRepository, AmbienteVirtualRepository $ambientevirtualRepository)
    {
        $this->cursoRepository = $cursoRepository;
        $this->ambientevirtualRepository = $ambientevirtualRepository;
    }

    public function getIndex()
    {
        $ambientes = $this->ambientevirtualRepository->findAmbientesWithMonitor();
        return view('Monitoramento::tempoonline.index', compact('ambientes'));
    }

    public function getMonitorar($idAmbiente)
    {
        $ambientevirtual = $this->ambientevirtualRepository->find($idAmbiente);

        if (is_null($ambientevirtual)) {
            flash()->error('Ambiente não existe!');
            return redirect()->back();
        }

        $ambiente = $this->ambientevirtualRepository->findAmbienteWithMonitor($idAmbiente);

        $timeclicks = Configuracao::get('time_between_clicks');
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        $wsfunction = $ambiente->ser_slug;

        return view('Monitoramento::tempoonline.monitorar', compact('cursos', 'ambiente', 'timeclicks', 'wsfunction'));
    }
}
