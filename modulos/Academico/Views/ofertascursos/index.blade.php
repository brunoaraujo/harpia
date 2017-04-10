@extends('layouts.modulos.academico')

@section('title')
    Ofertas de Cursos
@stop

@section('subtitle')
    Gerenciamento de ofertas de cursos
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form method="GET" action="{{ url('/academico/ofertascursos/index') }}">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="ofc_ano" id="ofc_ano" value="{{Input::get('ofc_ano')}}" placeholder="Ano da Oferta">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="crs_nome" id="crs_nome" value="{{Input::get('crs_nome')}}" placeholder="Nome do Curso">
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    @if(!is_null($tabela))
        <div class="box box-primary">
            <div class="box-header">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links() !!}</div>

    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
