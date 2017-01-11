@extends('layouts.modulos.academico')

@section('title')
    Alunos
@stop

@section('subtitle')
    Cadastro de alunos
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Cadastro de Alunos</h3>
                <span class="label label-success pull-right">Aluno</span>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/academico/alunos/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}

            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Salvar Aluno', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop