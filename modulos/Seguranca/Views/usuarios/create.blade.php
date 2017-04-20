@extends('layouts.modulos.seguranca')

@section('title')
    Usuários
@stop

@section('subtitle')
    Cadastro de usuários
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Cadastro de Usuários</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'seguranca.usuarios.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
            <h4 class="box-title">
                Dados de Usuário
            </h4>
            @include('Seguranca::usuarios.includes.formulario')

            <hr>
            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop