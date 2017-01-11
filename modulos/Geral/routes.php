<?php

Route::group(['prefix' => 'geral', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Geral\Http\Controllers\IndexController@getIndex');
    });

    Route::group(['prefix' => 'pessoas'], function () {
        Route::get('/index', '\Modulos\Geral\Http\Controllers\PessoasController@getIndex')->name('geral.pessoas.index');
        Route::get('/create', '\Modulos\Geral\Http\Controllers\PessoasController@getCreate')->name('geral.pessoas.getCreate');
        Route::post('/create', '\Modulos\Geral\Http\Controllers\PessoasController@postCreate')->name('geral.pessoas.postCreate');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\PessoasController@getEdit')->name('geral.pessoas.getEdit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\PessoasController@putEdit')->name('geral.pessoas.putEdit');
        Route::get('/show/{id}', '\Modulos\Geral\Http\Controllers\PessoasController@getShow')->name('geral.pessoas.show');
        Route::post('/verificapessoa', '\Modulos\Geral\Http\Controllers\PessoasController@postVerificaPessoa')->name('geral.pessoas.verificapessoa');
    });


    Route::group(['prefix' => 'documentos'], function () {
        Route::get('/create/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@getCreate')->name('geral.documentos.getCreate');
        Route::post('/create/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@postCreate')->name('geral.documentos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@getEdit')->name('geral.documentos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@putEdit')->name('geral.documentos.putEdit');
        Route::post('/delete', '\Modulos\Geral\Http\Controllers\DocumentosController@postDelete')->name('geral.documentos.delete');
        Route::get('/anexo/{id}', '\Modulos\Geral\Http\Controllers\DocumentosController@getDocumentoAnexo')->name('geral.documentos.getAnexo');
    });


    Route::group(['prefix' => 'titulacoes'], function () {
        Route::get('/index', '\Modulos\Geral\Http\Controllers\TitulacoesController@getIndex')->name('geral.titulacoes.index');
        Route::get('/create', '\Modulos\Geral\Http\Controllers\TitulacoesController@getCreate')->name('geral.titulacoes.getCreate');
        Route::post('/create', '\Modulos\Geral\Http\Controllers\TitulacoesController@postCreate')->name('geral.titulacoes.postCreate');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesController@getEdit')->name('geral.titulacoes.getEdit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesController@putEdit')->name('geral.titulacoes.putEdit');
        Route::post('/delete', '\Modulos\Geral\Http\Controllers\TitulacoesController@postDelete')->name('geral.titulacoes.delete');
    });

    Route::group(['prefix' => 'titulacoesinformacoes'], function () {
        Route::get('/create/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@getCreate')->name('geral.titulacoesinformacoes.getCreate');
        Route::post('/create/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@postCreate')->name('geral.titulacoesinformacoes.postCreate');
        Route::get('/edit/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@getEdit')->name('geral.titulacoesinformacoes.getEdit');
        Route::put('/edit/{id}', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@putEdit')->name('geral.titulacoesinformacoes.putEdit');
        Route::post('/delete', '\Modulos\Geral\Http\Controllers\TitulacoesInformacoesController@postDelete')->name('geral.titulacoesinformacoes.delete');
    });

    Route::group(['prefix' => 'async'], function () {
        Route::group(['prefix' => 'pessoas'], function () {
            //Route::get('/verificapessoa/{cpf}', '\Modulos\Geral\Http\Controllers\Async\Pessoas@getVerificapessoa');
        });
    });
});
