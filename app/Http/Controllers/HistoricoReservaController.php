<?php

namespace App\Http\Controllers;

use App\Models\HistoricoReserva;
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
     * Visualizar todas as alteracoes de historico de reservas de um usuario especifico
     */
    public function showUser($id)
    {
        $historicoReservaUsuario = HistoricoReserva::where('id_usuario', $id);

        if (!$historicoReservaUsuario) {
            return response()->json([
                'error' => true,
                'message' => 'Historico de alteracao nao encontrado',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Historico de alteracao listado com sucesso',
            'historico' => $historicoReservaUsuario,
        ], 200);
    }
}
