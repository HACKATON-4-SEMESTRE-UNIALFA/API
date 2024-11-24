<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\HistoricoReservaController;
use App\Http\Controllers\HorarioFuncionamentoController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\ReservasController;

//Login de Usuarios
Route::post('/login', [UsuarioController::class, 'login']); //Realiza o login e gera o token de usuarios cadastrados
Route::post('/usuarios', [UsuarioController::class, 'store']); // Criar um novo usuário
Route::get('/imagens/{filename}', [AmbienteController::class, 'showImage']); //Retorna um file da imagem


Route::middleware(['auth.jwt'])->group(function () {

    //Ambientes
    Route::get('/ambientes', [AmbienteController::class, 'index']); // Listas Ambientes
    Route::get('/ambientes/{id}', [AmbienteController::class, 'show']); // Lista Ambiente por ID
    Route::post('/ambientes', [AmbienteController::class, 'store']); //Cadastra novos ambientes
    Route::put('/ambientes/{id}', [AmbienteController::class, 'update']); //Edita usuario por id
    Route::put('/ambientes/desabilita/{id}', [AmbienteController::class, 'desable']); //Deleta usuario por id

    //Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index']); // Listar todos os usuários
    Route::put('/usuarios/desabilita/{id}', [UsuarioController::class, 'desable']); // Deletar um usuário
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']); // Mostrar um usuário específico
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update']); // Atualizar um usuário existente

    //Horario de Funcionamento
    Route::get('/horarios', [HorarioFuncionamentoController::class, 'index']); // Listar todos os horarios
    Route::delete('/horarios/{id}', [HorarioFuncionamentoController::class, 'destroy']); // Deletar um horario
    Route::get('/horarios/{id}', [HorarioFuncionamentoController::class, 'show']); // Mostrar um horario específico
    Route::post('/horarios', [HorarioFuncionamentoController::class, 'store']); // Criar um novo horario
    Route::put('/horarios/{id}', [HorarioFuncionamentoController::class, 'update']); // Atualizar um horario existente
    Route::get('/horarios/ambiente/{id_ambiente}', [HorarioFuncionamentoController::class, 'horariosAmbiente']); // Atualizar um horario existente


    //Reservas
    Route::get('/reservas', [ReservasController::class, 'index']); // Listar todos os reservas
    Route::put('/reservas/desativa/{id}', [ReservasController::class, 'desable']); // Desativar uma reserva
    Route::put('/reservas/ativa/{id}', [ReservasController::class, 'enable']); // Ativar uma reserva
    Route::get('/reservas/{id}', [ReservasController::class, 'show']); // Mostrar um reserva específico
    Route::get('/verificaReservaDia/{id}', [ReservasController::class, 'verificaReservaDia']); // Retorna os dias que estao com todos os horarios preenchidos
    Route::get('/verificaReservaHorario/{id}/{data}', [ReservasController::class, 'verificaReservaHorario']); // Retorna os horarios preenchidos e um dia
    Route::post('/reservas', [ReservasController::class, 'store']); // Criar um novo reserva
    Route::put('/reservas/{id}', [ReservasController::class, 'update']); // Atualizar um reserva existente

    //Historico de Reservas
    Route::get('/reserva/historico', [HistoricoReservaController::class, 'index']); // Listar todos os reservas
    Route::get('/reserva/historico/{id}', [HistoricoReservaController::class, 'show']); // Mostrar um reserva específico

    //Notificacao
    Route::get('/reserva/notificacao', [NotificacaoController::class, 'index']); // Listar todas as notificacoes
    Route::get('/reserva/notificacao/{id}', [NotificacaoController::class, 'show']); // Mostrar uma notificacao especifica
    Route::put('/reserva/desabilita/{id}', [NotificacaoController::class, 'desabilita']); // Marca como visualizada a notificacao

});
