@extends('layouts.modulos.academico')

@section('title')
    Módulos
@stop

@section('subtitle')
    Gerenciamento de matrizes curriculares :: {{$curso->crs_nome}} :: {{$matrizcurricular->mtc_titulo}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')

    @if(!$modulos->isEmpty())

        @foreach($modulos as $modulo)
          <div class="panel box box-primary">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                  {{$modulo->mdo_nome}}
                </a>
              </h4>

                <div class="box-tools pull-right">
                    {!! ActionButton::grid([
                        'type' => 'LINE',
                        'buttons' => [
                        [
                            'classButton' => 'btn btn-box-tool',
                            'icon' => 'fa fa-pencil',
                            'action' => '/academico/modulosmatrizes/edit/'.$modulo->mdo_id,
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn btn-box-tool',
                            'icon' => 'fa fa-trash',
                            'action' => '/academico/modulosmatrizes/delete',
                            'id' => $modulo->mdo_id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                    ]) !!}
                </div>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
              <div class="box-body">
                @if(!$modulo->disciplinas->isEmpty())
                    <table  class="table table-bordered">
                      <thead>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Carga Horária</th>
                        <th>Créditos</th>
                        <th>Ações<th>
                      </thead>
                      <tbody>
                        @foreach($modulo->disciplinas as $disciplina)
                          <tr>
                            <td>{{$disciplina->dis_id}}</td>
                            <td>{{$disciplina->dis_nome}}</td>
                            <td>{{$disciplina->dis_carga_horaria}} horas</td>
                            <td>{{$disciplina->dis_creditos}}</td>
                            <td>
                              <a href="#" class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                              </a>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                @else
                  <p>
                    Sem disciplinas cadastradas
                  </p>
                @endif
              </div>
              <div class="box-footer">
                {!! ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                        [
                            'classButton' => 'btn btn-success',
                            'icon' => 'fa fa-plus',
                            'action' => '/academico/modulosmatrizes/adicionardisciplinas/'.$modulo->mdo_id,
                            'label' => 'Adicionar Disciplinas',
                            'method' => 'get'
                        ],
                    ]
                ])
                !!}
              </div>
            </div>
          </div>
        @endforeach
    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
