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

        if (!$notificacao) {
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
            'visualizacao' => false,
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
    public function show($id)
    {
        $notificacao = Notificacao::find($id);

        if (!$notificacao) {
            return response()->json([
                'error' => true,
                'message' => 'Notificacao nao encontrada'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Notificacao listada',
            'notificacao' => $notificacao,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function desable($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado'
            ], 404);
        }

        $notificacoes = Notificacao::where('id_usuario', $id)
            ->where('visualizacao', false)
            ->get();

        if ($notificacoes->isEmpty()) {
            return response()->json([
                'error' => false,
                'message' => 'Nenhuma notificação pendente para este usuário'
            ], 200);
        }

        foreach ($notificacoes as $notificacao) {
            $notificacao->visualizacao = true;
            $notificacao->save();
        }

        return response()->json([
            'error' => false,
            'message' => 'Todas as notificações foram marcadas como visualizadas',
            'notificacao' => $notificacoes->count(),
        ], 200);
    }
}
