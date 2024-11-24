<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\HistoricoReservaController;
use App\Http\Controllers\HorarioFuncionamentoController;
use App\Http\Controllers\ReservasController;

//Login de Usuarios
Route::post('/login', [UsuarioController::class, 'login']); //Realiza o login e gera o token de usuarios cadastrados


//Ambientes
Route::get('/imagens', [AmbienteController::class, 'showImage']);
Route::get('/ambientes', [AmbienteController::class, 'index']);// Listas Ambientes
Route::get('/ambientes/{id}', [AmbienteController::class, 'show']);// Lista Ambiente por ID
Route::post('/ambientes', [AmbienteController::class, 'store']);//Cadastra novos ambientes
Route::put('/ambientes/{id}', [AmbienteController::class, 'update']);//Edita usuario por id
Route::delete('/ambientes/{id}', [AmbienteController::class, 'destroy']);//Deleta usuario por id

//Usuarios
Route::get('/usuarios', [UsuarioController::class, 'index']); // Listar todos os usuários
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']); // Deletar um usuário
Route::get('/usuarios/{id}', [UsuarioController::class, 'show']); // Mostrar um usuário específico
Route::post('/usuarios', [UsuarioController::class, 'store']); // Criar um novo usuário
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']); // Atualizar um usuário existente

//Horario de Funcionamento
Route::get('/horarios', [HorarioFuncionamentoController::class, 'index']); // Listar todos os horarios
Route::delete('/horarios/{id}', [HorarioFuncionamentoController::class, 'destroy']); // Deletar um horario
Route::get('/horarios/{id}', [HorarioFuncionamentoController::class, 'show']); // Mostrar um horario específico
Route::post('/horarios', [HorarioFuncionamentoController::class, 'store']); // Criar um novo horario
Route::put('/horarios/{id}', [HorarioFuncionamentoController::class, 'update']); // Atualizar um horario existente

//Reservas
Route::get('/reservas', [ReservasController::class, 'index']); // Listar todos os reservas
Route::delete('/reservas/{id}', [ReservasController::class, 'destroy']); // Deletar um reserva
Route::put('/reservas/desabilita/{id}', [ReservasController::class, 'desabilita']); // Desabilita um reserva
Route::get('/reservas/{id}', [ReservasController::class, 'show']); // Mostrar um reserva específico
Route::get('/verificaReserva', [ReservasController::class, 'verificaReserva']); // Mostrar um reserva específico
Route::post('/reservas', [ReservasController::class, 'store']); // Criar um novo reserva
Route::put('/reservas/{id}', [ReservasController::class, 'update']); // Atualizar um reserva existente


//Historico de Reservas
Route::get('/reserva/historico', [HistoricoReservaController::class, 'index']); // Listar todos os reservas
Route::get('/reserva/historico/{id}', [HistoricoReservaController::class, 'show']); // Mostrar um reserva específico

