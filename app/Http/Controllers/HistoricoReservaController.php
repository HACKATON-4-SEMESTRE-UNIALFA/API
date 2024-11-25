<?php

namespace App\Http\Controllers;

use App\Models\HistoricoReserva;
use App\Models\Reservas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoricoReservaController extends Controller
{
    /**
     * Visualizar todas as reservas independente de usuario ou id reserva
     */
    public function index()
    {
        $historicoReserva = HistoricoReserva::all();

        if (!$historicoReserva) {
            return response()->json([
                'error' => true,
                'message' => 'Historico de alteracoes nao encontrado',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Historico de alteracoes listado com sucesso',
            'historico' => $historicoReserva,
        ], 200);
    }

    /**
     * Visualizar todas as alteracoes de uma reserva especifica
     */
    public function show($id)
    {
        $historicoReserva = HistoricoReserva::all($id);

        if (!$historicoReserva) {
            return response()->json([
                'error' => true,
                'message' => 'Historico de alteracao nao encontrado',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Historico de alteracao listado com sucesso',
            'historico' => $historicoReserva,
        ], 200);
    }

    /**
     * Visualizar todas as alteracoes de historico de reservas de uma reserva especifica
     */
    public function showUser($id)
    {
        $historicoReserva = HistoricoReserva::with(['ambiente', 'alteracao'])
            ->where('id_reserva', $id)
            ->get();

        if ($historicoReserva->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'Historico de alteracao nao encontrado',
            ], 404);

        }

        return response()->json([
            'error' => false,
            'message' => 'Histórico de alteração listado com sucesso',
            'historico' => $historicoReserva->map(function ($item) {
                return [
                    'id_reserva' => $item->id_reserva,
                    'nome_alteracao' => $item->alteracao->nome ?? 'Usuário não encontrado',
                    'ambiente' => $item->ambiente->nome ?? 'Ambiente não encontrado',
                    'horario' => $item->horario,
                    'data' => $item->data,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            }),
        ], 200);
    }
}
