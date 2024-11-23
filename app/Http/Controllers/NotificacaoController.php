<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Notificacao;
use App\Models\Usuario;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    /**
     * Retorna todas as notificacoes
     */
    public function index()
    {
        $notificacao = Notificacao::all();

        if(!$notificacao){
            return response()->json([
                'error' => true,
                'message' => 'Nenhuma notificacao encontrada',
            ], 404);
        }

        return response()->json([
            'error' => true,
            'notificacao' => $notificacao,
        ], 200);
    }

    /**
     * Cria uma nova linha no banco de notificacao
     */
    public static function store($reserva, $id_usuario, $tipo, $mensagem)
    {

        $usuario = Usuario::find($id_usuario);

        $ambiente = Ambiente::find($reserva->id_ambiente);



        $infoReserva = "{$ambiente->nome}, {$reserva->data}, {$reserva->horario}";

        $notificacao = Notificacao::create([
            'id_reserva' => $reserva->id,
            'id_usuario' => $usuario->id,
            'infoReserva' => $infoReserva,
            'tipo' => $tipo,
            'mensagem' => $mensagem,
            'visualizacao' => true,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Notificacao criada',
            'notificacao' => $notificacao,
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Notificacao $notificacao)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notificacao $notificacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notificacao $notificacao)
    {
        //
    }
}
