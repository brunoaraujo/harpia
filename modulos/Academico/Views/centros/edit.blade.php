@extends('layouts.modulos.seguranca')

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Centro
@stop

@section('subtitle')
    Alterar centro :: {{$centro->cen_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Centro</h3>
        </div>
        <div class="box-body">
            {!! Form::model($centro,["url" => url('/') . "/academico/centros/edit/$centro->cen_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            @include('Academico::centros.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("select").select2();
            });
        </script>

@endsection
