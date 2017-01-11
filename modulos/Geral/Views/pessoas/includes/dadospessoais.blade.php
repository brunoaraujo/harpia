@section('stylesheets')
    <style>
        .title-box {
            display: inline-block;
            font-size: 18px;
            margin: 0;
            line-height: 1;
            font-family: 'Source Sans Pro', sans-serif;
        }
    </style>
@stop
<!--  Dados Pessoais  -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados Pessoais</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Nome Completo: </strong> {{$pessoa->pes_nome}}</p>
                        <p><strong>Email: </strong> {{$pessoa->pes_email}}</p>
                        <p><strong>Sexo: </strong> {{($pessoa->pes_sexo == 'M') ? 'Masculino' : 'Feminino' }}</p>
                        <p><strong>Data de Nascimento: </strong> {{$pessoa->pes_nascimento}}</p>
                        <p><strong>Telefone: </strong> {{Format::mask($pessoa->pes_telefone, '(##) #####-####')}}</p>
                        <p><strong>Estado Civil: </strong> {{ucfirst($pessoa->pes_estado_civil)}}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Nome da Mãe: </strong> {{$pessoa->pes_mae}}</p>
                        <p><strong>Nome do Pai: </strong> {{$pessoa->pes_pai}}</p>

                        <p><strong>Naturalidade: </strong> {{$pessoa->pes_naturalidade}}</p>

                    </div>
                    <div class="col-md-4">
                        <p><strong>Nacionalidade: </strong> {{$pessoa->pes_nacionalidade}}</p>
                        <p><strong>Raça: </strong> {{ucfirst($pessoa->pes_raca)}}</p>
                        <p><strong>Necessidade Especial: </strong> {{($pessoa->pes_necessidade_especial == 'S') ? 'Sim' : 'Não'}}</p>
                        <p><strong>Estrangeiro: </strong> {{($pessoa->pes_estrangeiro) ? 'Sim' : 'Não'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Endereço</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Endereço: </strong> {{$pessoa->pes_endereco}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Complemento: </strong> {{$pessoa->pes_complemento}}</p>
                                        <p><strong>Número: </strong> {{$pessoa->pes_numero}}</p>
                                        <p><strong>Bairro: </strong> {{$pessoa->pes_bairro}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>CEP: </strong> {{$pessoa->pes_cep}}</p>
                                        <p><strong>Cidade: </strong> {{$pessoa->pes_cidade}}</p>
                                        <p><strong>Estado: </strong> {{$pessoa->pes_estado}}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>