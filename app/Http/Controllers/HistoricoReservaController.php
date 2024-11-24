<?php

namespace App\Http\Controllers;

use App\Models\HistoricoReserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoricoReservaController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Display the specified resource.
     */
    public function show($id)
    {
        $historicoReserva = HistoricoReserva::find($id);

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
     * Display the specified resource.
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
