@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Matriculas em Lote
@endsection

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
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::label('crs_id', 'Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha o Curso']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('ofc_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('trm_id', 'Turma*') !!}
                        <div class="form-group">
                            {!! Form::select('trm_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::label('per_id', 'Período Letivo*') !!}
                        <div class="form-group">
                            {!! Form::select('per_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('ofd_id', 'Disciplinas Ofertadas*') !!}
                        <div class="form-group">
                            {!! Form::select('ofd_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label for="">&nbsp;</label>
                        <div class="form-group">
                            <input type="submit" id="btnBuscar" class="form-control btn-primary" value="Buscar">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="box box-primary hidden" id="boxAlunos">
        <div class="box-header with-border">
            <h3 class="box-title">Lista de Alunos</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body"></div>
    </div>
@endsection

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function() {
            // select2
            $('select').select2();

            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');
            var periodosLetivosSelect = $('#per_id');
            var disciplinasOfertadasSelect = $('#ofd_id');
            var btnBuscar = $('#btnBuscar');

            // token
            var token = "{{csrf_token()}}";

            // evento change select de cursos
            $('#crs_id').change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar as ofertas de curso de acordo com o curso escolhido
                var cursoId = $(this).val();

                if(!cursoId || cursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + cursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        ofertasCursoSelect.append("<option value=''>Selecione a oferta</option>");
                        $.each(response, function (key, obj) {
                            ofertasCursoSelect.append('<option value="'+obj.ofc_id+'">'+obj.ofc_ano+' ('+obj.mdl_nome+')</option>');
                        });
                    } else {
                        ofertasCursoSelect.append("<option>Sem ofertas disponiveis</option>");
                    }
                });
            });

            // evento change select de ofertas de curso
            ofertasCursoSelect.change(function () {

                //limpando selects
                turmaSelect.empty();
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar as turmas de acordo com a oferta de curso
                var ofertaCursoId = $(this).val();

                if(!ofertaCursoId || ofertaCursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        turmaSelect.append('<option value="">Selecione a turma</option>');
                        $.each(response, function (key, obj) {
                            turmaSelect.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>');
                        });
                    } else {
                        turmaSelect.append('<option>Sem turmas disponíveis</option>');
                    }
                });
            });

            // evento change select de turmas
            turmaSelect.change(function () {

                // limpando selects
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar os periodos letivos de acordo com a turma escolhida
                var turmaId = $(this).val();

                if(!turmaId || turmaId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/" + turmaId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        periodosLetivosSelect.append('<option value="">Selecione o periodo letivo</option>');
                        $.each(response, function (key, obj) {
                            periodosLetivosSelect.append('<option value="'+obj.per_id+'">'+obj.per_nome+'</option>');
                        });
                    } else {
                        periodosLetivosSelect.append('<option>Sem periodos letivos disponiveis</option>');
                    }
                });
            });

            //evento change select de periodos letivos
            periodosLetivosSelect.change(function () {

                // limpando select
                disciplinasOfertadasSelect.empty();

                // buscar todas as disciplinas ofertadas de acordo com o periodo e a turma
                var turmaId = turmaSelect.val();
                var periodoLetivoId = $(this).val();

                if((!turmaId || turmaId == '') || (!periodoLetivoId || periodoLetivoId == '')) {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertasdisciplinas/findall?ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoLetivoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        disciplinasOfertadasSelect.append('<option value="">Selecione a disciplina ofertada</option>');
                        $.each(response, function (key, obj) {
                            disciplinasOfertadasSelect.append('<option value="'+obj.ofd_id+'">'+obj.dis_nome+'</option>');
                        });
                    } else {
                        disciplinasOfertadasSelect.append('<option>Sem disciplinas ofertadas disponíveis</option>');
                    }
                })
            });

            // evento do botao Buscar
            btnBuscar.click(function (event) {

                // parar o evento de submissao do formaulario
                event.preventDefault();

                var turmaId = turmaSelect.val();
                var ofertaDisciplinaId = disciplinasOfertadasSelect.val();

                if((!turmaId || turmaId == '') || (!ofertaDisciplinaId || ofertaDisciplinaId == '')) {
                    return false;
                }

                renderTable(turmaId, ofertaDisciplinaId);

            });

            var renderTable = function(turmaId, ofertaDisciplinaId) {
                $.harpia.httpget("{{url('/')}}/academico/async/matriculasofertasdisciplinas/getalunosmatriculaslote/"+turmaId+"/" + ofertaDisciplinaId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        var html = '<div class="row"><div class="col-md-12">';

                        var naoMatriculados = new Array();
                        var cursandos = new Array();
                        var aprovados = new Array();

                        $.each(response, function (key, obj) {
                            if(obj.mof_situacao_matricula == null || obj.mof_situacao_matricula == 'no_pre_requisitos') {
                                naoMatriculados.push(obj);
                            } else if (obj.mof_situacao_matricula == 'cursando') {
                                cursandos.push(obj);
                            } else if(['aprovado_media', 'aprovado_final'].indexOf(obj.mof_situacao_matricula) > -1) {
                                aprovados.push(obj);
                            }
                        });

                        // criando a estrutura das tabs
                        var tabs = '<div class="nav-tabs-custom">';
                        tabs += '<ul class="nav nav-tabs">';
                        tabs += '<li class="active">' +
                                '<a href="#tab_1" data-toggle="tab">' +
                                'Não Matriculados '+
                                '<span data-toggle="tooltip" class="badge bg-blue">'+naoMatriculados.length+'</span>'+
                                '</a></li>';
                        tabs += '<li>' +
                                '<a href="#tab_2" data-toggle="tab">' +
                                'Cursando <span data-toggle="tooltip" class="badge bg-blue">'+cursandos.length+'</span>' +
                                '</a></li>';
                        tabs += '<li>' +
                                '<a href="#tab_3" data-toggle="tab">' +
                                'Aprovados <span data-toggle="tooltip" class="badge bg-blue">'+aprovados.length+'</span>' +
                                '</a></li>';
                        tabs += '</ul>';
                        tabs += '<div class="tab-content">';


                        // Criacao da Tab de Alunos nao matriculados para matricula
                        var tab1 = '<div class="tab-pane active" id="tab_1">';

                        if(!$.isEmptyObject(naoMatriculados)) {
                            var div = '<div class="row"><div class="col-md-12">';
                            var table1 = '<table class="table table-bordered table-striped">';

                            // cabeçalho da tabela
                            table1 += '<tr>';
                            table1 += '<th width="1%"><label><input id="select_all" type="checkbox"></label></th>';
                            table1 += '<th>Nome</th>';
                            table1 += '<th width="20%">Situacao de Matricula</th>';
                            table1 += '</tr>';

                            // corpo da tabela
                            $.each(naoMatriculados, function (key, obj) {
                                table1 += '<tr>';
                                if(obj.mof_situacao_matricula == 'no_pre_requisitos') {
                                    table1 += '<td></td>';
                                } else {
                                    table1 += '<td><label><input class="matriculas" type="checkbox" value="'+obj.mat_id+'"></label></td>';
                                }

                                table1 += '<td>'+obj.pes_nome+'</td>';
                                if(obj.mof_situacao_matricula == 'no_pre_requisitos') {
                                    table1 += '<td><span class="label label-warning">Pré-requisitos não satisfeitos</span></td>';
                                } else {
                                    table1 += '<td><span class="label label-success">Apto para Matricula</span></td>';
                                }

                                table1 += '</tr>';
                            });

                            table1 += '</table>';
                            div += table1;
                            div += '</div></div>';

                            // criacao do botao de matricular alunos
                            div += '<div class="row"><div class="col-md-12">';
                            div += '<button class="btn btn-success hidden btnMatricular">Matricular Alunos</button>';
                            div += '</div></div>';

                            tab1 += div;
                        } else {
                            tab1 += '<p>Sem alunos para matricular</p>';
                        }

                        tab1 += '</div>';

                        // Criacao da Tab de Alunos cursando a disciplina
                        var tab2 = '<div class="tab-pane" id="tab_2">';

                        if(!$.isEmptyObject(cursandos)) {
                            var table2 = '<table class="table table-bordered table-striped">';

                            // cabeçalho da tabela
                            table2 += '<tr>';
                            table2 += '<th>Nome</th>';
                            table2 += '<th width="20%">Situacao de Matricula</th>';
                            table2 += '</tr>';

                            // corpo da tabela
                            $.each(cursandos, function (key, obj) {
                                table2 += '<tr>';
                                table2 += '<td>'+obj.pes_nome+'</td>';
                                table2 += '<td><span class="label label-info">Cursando</span></td>';
                                table2 += '</tr>';
                            });

                            table2 += '</table>';
                            tab2 += table2;
                        } else {
                            tab2 += '<p>Sem alunos cursando a disciplina</p>';
                        }

                        tab2 += '</div>';

                        // Criacao da Tab de Alunos aprovados na disciplina
                        var tab3 = '<div class="tab-pane" id="tab_3">';

                        if(!$.isEmptyObject(aprovados)) {
                            var table3 = '<table class="table table-bordered table-striped">';

                            // cabeçalho da tabela
                            table3 += '<tr>';
                            table3 += '<th>Nome</th>';
                            table3 += '<th width="20%">Situacao de Matricula</th>';
                            table3 += '</tr>';

                            // corpo da tabela
                            $.each(aprovados, function (key, obj) {
                                table3 += '<tr>';
                                table3 += '<td>'+obj.pes_nome+'</td>';
                                table3 += '<td><span class="label label-success">Aprovado</span></td>';
                                table3 += '</tr>';
                            });

                            table3 += '</table>';
                            tab3 += table3;
                        } else {
                            tab3 += '<p>Sem alunos aprovados na disciplina</p>';
                        }

                        tab3 += '</div>';

                        tabs += tab1;
                        tabs += tab2;
                        tabs += tab3;

                        tabs += '</div></div>';

                        html += tabs;
                        html += '</div></div>';

                        $('#boxAlunos').removeClass('hidden');
                        $('#boxAlunos .box-body').empty().append(html);

                    } else {
                        $('#boxAlunos').removeClass('hidden');
                        $('#boxAlunos .box-body').empty().append('<p>Sem alunos matriculados na turma</p>');
                    }
                });
            };

            // evento para selecionar todos os checkboxes
            $(document).on('click', '#select_all',function(event) {
                if(this.checked) {
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                }
                else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            var hiddenButton = function () {
                var checkboxes = $('#boxAlunos table td input[type="checkbox"]');

                if(checkboxes.is(':checked')){
                    $(document).find('.btnMatricular').removeClass('hidden');
                }else{
                    $(document).find('.btnMatricular').addClass('hidden');
                }
            };

            $(document).on('click', '#boxAlunos table input[type="checkbox"]', hiddenButton);

            $(document).on('click', '.btnMatricular', function () {
                var quant = $('.matriculas:checked').length;

                var ofertaId = $('#ofd_id').val();

                if((!(quant > 0)) || (!ofertaId || ofertaId == '')) {
                    return false;
                }

                var matriculasIds = new Array();

                $('.matriculas:checked').each(function () {
                    matriculasIds.push($(this).val());
                });

                sendMatriculas(matriculasIds, ofertaId);
            });

            var sendMatriculas = function(matriculasIds, ofertaId) {

                var dados = {
                    matriculas: matriculasIds,
                    ofd_id: ofertaId,
                    _token: token
                };

                $.harpia.showloading();

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/matriculasofertasdisciplinas/matriculaslote',
                    data: dados,
                    success: function (data) {
                        $.harpia.hideloading();

                        toastr.success('Alunos matriculados com sucesso!', null, {progressBar: true});

                        var turma = turmaSelect.val();
                        var ofertaDisciplina = disciplinasOfertadasSelect.val();

                        renderTable(turma, ofertaDisciplina);
                    },
                    error: function (xhr, textStatus, error) {
                        $.harpia.hideloading();

                        switch (xhr.status) {
                            case 400:
                                toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                                break;
                            default:
                                toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                        }
                    }
                });
            };
        });
    </script>
@stop
