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
     * Store a newly created resource in storage.
     */
    public static function store($reservaAtual, $id)
    {
        $validator = Validator::make([
            'id_reserva' => $id,
            'ambienteAnterior' => strval($reservaAtual->id_ambiente),
            'horarioAnterior' => $reservaAtual->horario,
            'dataAnterior' => $reservaAtual->dia,
            'statusAnterior' => $reservaAtual->status ?? false,
        ], [
            'id_reserva' => 'required|exists:reservas,id',
            'ambienteAnterior' => 'required|string',
            'horarioAnterior' => 'required|string',
            'dataAnterior' => 'required|date_format:Y-m-d',
            'statusAnterior' => 'required|boolean',

        ], [
            'required' => 'O campo :attribute e obrigatorio',
            'exists' => 'O campo :attribute nao existe na tabela reservas.',
            'string' => 'O campo :attribute deve ser string',
            'date_format' => 'O campo :attribute deve estar no formato correto :format',
            'boolean' => 'O campo :attribute deve ser booleano(true/false)',
        ], [
            'id_reserva' => 'ID Reserva',
            'ambienteAnterior' => 'Ambiente Anterior',
            'horarioAnteror' => 'Horario Anterior',
            'dataAnteror' => 'Data Anterior',
            'statusAnteror' => 'Status Anterior',
        ], 422);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados do historico',
                'errors' => $validator->errors(),
            ], 422);
        }

        $historico = HistoricoReserva::create([
            'id_reserva' => $id,
            'ambienteAnterior' => $reservaAtual->id_ambiente,
            'horarioAnterior' => $reservaAtual->horario,
            'dataAnterior' => $reservaAtual->dia,
            'statusAnterior' => $reservaAtual->status ?? false,
        ]);

        return $historico;


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
}
