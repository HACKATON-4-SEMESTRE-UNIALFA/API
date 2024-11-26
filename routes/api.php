<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\BlackListController;
use App\Http\Controllers\HistoricoReservaController;
use App\Http\Controllers\HorarioFuncionamentoController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\Relatorio;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\WhiteListController;

//Login e Cadastro de Usuarios
Route::post('/login', [UsuarioController::class, 'login']); //Realiza o login e gera o token de usuarios cadastrados
Route::post('/usuarios', [UsuarioController::class, 'store']); // Criar um novo usuário


//Rotas de acesso aos ambientes
Route::get('/imagens/{filename}', [AmbienteController::class, 'showImage']); //Retorna um file da imagem
Route::post('/ambientes/{id}/usuario/{id_alteracao}', [AmbienteController::class, 'update']); //Edita o ambiente e salva quem fez a alteração
Route::post('/ambientes', [AmbienteController::class, 'store']); //Cadastra novos ambientes



Route::middleware(['auth.jwt'])->group(function () {

    //Relatorios
    Route::get('/relatorio/ambientes/status', [Relatorio::class, 'showRelatorioAmbienteStatus']);//Lista as quantidades de status dos ambientes
    Route::get('/relatorio/reservas/status', [Relatorio::class, 'showRelatorioReservas']);//Lista as quantidades de status dos ambientes

    //Ambientes
    Route::get('/ambientes', [AmbienteController::class, 'index']); // Listas Ambientes
    Route::get('/ambientes/{id}', [AmbienteController::class, 'show']); // Lista Ambiente por ID
    Route::put('/ambientes/desabilita/{id}', [AmbienteController::class, 'desable']); //Desabilita o ambiente
    Route::get('/ambiente/disponivel', [AmbienteController::class, 'EnableAll']); //Mostra todos os ambientes disponiveis


    //Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index']); // Listar todos os usuários
    Route::put('/usuarios/desabilita/{id}', [UsuarioController::class, 'desable']); // Deletar um usuário
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']); // Mostrar um usuário específico
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update']); // Atualizar um usuário existente


    //Horario de Funcionamento
    Route::get('/horarios', [HorarioFuncionamentoController::class, 'index']); // Listar todos os horarios
    Route::delete('/horarios/{id}', [HorarioFuncionamentoController::class, 'destroy']); // Deletar um horario
    Route::post('/horarios', [HorarioFuncionamentoController::class, 'store']); // Criar um novo horario
    Route::get('/horarios/{id}', [HorarioFuncionamentoController::class, 'show']); // Mostrar um horario específico
    Route::put('/horarios/{id}', [HorarioFuncionamentoController::class, 'update']); // Atualizar um horario existente
    Route::get('/horarios/ambiente/{id_ambiente}', [HorarioFuncionamentoController::class, 'horariosAmbiente']); // Atualizar um horario existente


    //Reservas
    Route::post('/reservas', [ReservasController::class, 'store']); // Criar um novo reserva
    Route::get('/reservas', [ReservasController::class, 'index']); // Listar todos os reservas
    Route::get('/reservas/{id}', [ReservasController::class, 'show']); // Mostrar um reserva específico
    Route::get('/verificaReservaDia/{id}', [ReservasController::class, 'verificaReservaDia']); // Retorna os dias que estao com todos os horarios preenchidos
    Route::get('/verificaReservaHorario/{id}/{data}', [ReservasController::class, 'verificaReservaHorario']); // Retorna os horarios preenchidos e um dia
    Route::put('/reservas/desativa/{id}/usuario/{id_alteracao}', [ReservasController::class, 'desable']); // Desativar uma reserva
    Route::put('/reservas/ativa/{id}', [ReservasController::class, 'enable']); // Ativar uma reserva
    Route::put('/reservas/{id}', [ReservasController::class, 'update']); // Atualizar um reserva existente
    Route::get('/reservas/usuario/{id}', [ReservasController::class, 'showUserReserva']); // Atualizar um reserva existente
    Route::put('/reserva/confirmada', [ReservasController::class, 'confirmaReserva']); // Atualizar as reservas para confirmadas


    //Historico de Reservas
    Route::get('/reserva/historico', [HistoricoReservaController::class, 'index']); // Listar todos os reservas
    Route::get('/reserva/historico/{id}', [HistoricoReservaController::class, 'show']); // Mostrar um reserva específico
    Route::get('/reserva/{id}/historico', [HistoricoReservaController::class, 'showUser']); // Mostrar um reserva específico


    //Notificacao
    Route::put('/notificacoes/marcarTodas/{id}', [NotificacaoController::class, 'enableView']); // Marca como visualizada a notificacao
    Route::get('/reserva/notificacao', [NotificacaoController::class, 'index']); // Listar todas as notificacoes
    Route::get('/reserva/notificacao/{id}', [NotificacaoController::class, 'show']); // Mostrar uma notificacao especifica
    Route::get('/notificacoes/visualizadas/{id}', [NotificacaoController::class, 'usuarioViewFalse']); // Marca como visualizada a notificacao
    Route::get('/notificacoes/usuario/{id}', [NotificacaoController::class, 'usuarioAllNotificacao']); // Marca como visualizada a notificacao


    //Whitelist
    Route::get('/whitelist/{id}', [WhitelistController::class, 'show']); // Mostrar uma data da whitelist
    Route::get('/whitelist', [WhiteListController::class, 'index']); // Listar todas as datas da whitelist
    Route::post('/whitelist', [WhitelistController::class, 'store']); // Cadastrar uma data na whitelist
    Route::put('/whitelist/{id}', [WhitelistController::class, 'update']); // Atualizar uma data da whitelist
    Route::delete('/whitelist/{id}', [WhitelistController::class, 'destroy']); // Deletar uma data da whitelist


    //Blacklist
    Route::get('/blacklist', [BlackListController::class, 'index']); // Listar todas as datas da blacklist
    Route::get('/blacklist/{id}', [BlackListController::class, 'show']); // Mostrar uma data da blacklist
    Route::post('/blacklist', [BlackListController::class, 'store']); // Cadastrar uma data na blacklist
    Route::put('/blacklist/{id}', [BlackListController::class, 'update']); // Atualizar uma data da blacklist
    Route::delete('/blacklist/{id}', [BlackListController::class, 'destroy']); // Deletar uma data da blacklist

});
