<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/instalar-banco-render', function () {
    try {
        // O '--force' é obrigatório em produção
        Artisan::call('migrate', ['--force' => true]);
        return 'Tabelas do RotaCerta criadas com sucesso no Render! 🚀';
    } catch (\Exception $e) {
        return 'Deu erro: ' . $e->getMessage();
    }
});

Route::get('/', function () {
    return view('welcome');
});


