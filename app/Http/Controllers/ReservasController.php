<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\HistoricoReserva;
use App\Models\HorarioFuncionamento;
use App\Models\Reservas;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reserva = Reservas::all();

        if (!$reserva) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhuma reserva encontrada',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Reservas listadas com sucesso',
            'reserva' => $reserva
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_usuario' => 'required|exists:usuarios,id',
                'id_ambiente' => 'required|exists:ambientes,id',
                'horario' => 'required|string',
                'data' => 'required|date_format:Y-m-d',
                'status' => 'required|in:ativo,inativo,cancelado'
            ],
            [
                'required' => 'O campo :attribute e obrigatorio',
                'id_usuario.exists' => 'O :attribute informado nao existe na tabela usuarios',
                'id_ambientes.exists' => 'O :attribute informado nao existe na tabela ambientes',
                'date_format' => 'O :attribute deve estar no formato correto: :format.',
                'string' => 'O :attribute deve ser string',
            ],
            [
                'id_ambiente' => 'Id Ambiente',
                'id_usuario' => 'Id Usuario',
                'data' => 'data',
                'horario' => 'Horario',
                'status' => 'status'
            ],
            422
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'errors' => $validator->errors(),
            ], 404);
        }


        $historicoOk = HistoricoReserva::create([
            'id_usuario' => $request->input('id_usuario'),
            'id_ambiente' => $request->input('id_ambiente'),
            'horario' => $request->input('horario'),
            'data' => $request->input('data'),
            'status' => $request->input('status')
        ]);

        $reserva = Reservas::create([
            'id_usuario' => $request->input('id_usuario'),
            'id_ambiente' => $request->input('id_ambiente'),
            'horario' => $request->input('horario'),
            'data' => $request->input('data'),
            'status' => $request->input('status')
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Reserva cadastrada com sucesso!',
            'reserva' => $reserva,
            'historico' => $historicoOk,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reserva = Reservas::find($id);

        if (!$reserva) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum reserva encontrada'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Reserva encontrada',
            'reserva' => $reserva
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id_usuario' => 'required|exists:usuarios,id',
                'id_ambiente' => 'required|exists:ambientes,id',
                'horario' => 'required|string',
                'data' => 'required|date_format:Y-m-d',
                'status' => 'required|in:ativo,inativo,cancelado'
            ],
            [
                'required' => 'O campo :attribute e obrigatorio',
                'id_usuario.exists' => 'O :attribute informado nao existe na tabela usuarios',
                'id_ambientes.exists' => 'O :attribute informado nao existe na tabela ambientes',
                'date_format' => 'O :attribute deve estar no formato correto: :format.',
                'string' => 'O :attribute deve ser string',
            ],
            [
                'id_ambiente' => 'Id Ambiente',
                'id_usuario' => 'Id Usuario',
                'data' => 'data',
                'horario' => 'Horario',
                'status' => 'status'
            ],
            422
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'errors' => $validator->errors(),
            ], 404);
        }

        $reservaAtual = Reservas::find($id);

        $historico = HistoricoReserva::create([
            'id_reserva' => $reservaAtual->id,
            'id_alteracao' => $request->id_alteracao,
            'id_usuario' => $request->input('id_usuario'),
            'id_ambiente' => $request->input('id_ambiente'),
            'horario' => $request->input('horario'),
            'data' => $request->input('data'),
            'status' => $request->input('status')
        ]);


        $reservaAtual->update([
            'id_usuario' => $request->id_usuario,
            'id_ambiente' => $request->id_ambiente,
            'horario' => $request->horario,
            'data' => $request->data,
            'status' => $request->status
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Reserva editada com sucesso!',
            'reserva' => $reservaAtual,
            'historico' => $historico,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function desabilita($id)
    {
        $reserva = Reservas::find($id);

        if (!$reserva) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum reserva encontrada'
            ], 404);
        }

        $reserva->update([
            'statusReserva' => false,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Reserva desabilitada',
            'reserva' => $reserva
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reserva = Reservas::find($id);

        if (!$reserva) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum reserva encontrada'
            ], 404);
        }

        $reserva->delete($id);

        return response()->json([
            'error' => false,
            'message' => 'Reserva deletada',
            'reserva' => $reserva
        ], 200);
    }


    /**
     * 
     */
    public function verificaReserva(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'id_ambiente' => 'required|exists:ambientes,id',
            ],
            [
                'required' => 'O campo :attribute e obrigatorio',
                'id_ambientes.exists' => 'O :attribute informado nao existe na tabela ambientes',
            ],
            [
                'id_ambiente' => 'Id Ambiente',
            ],
            422
        );


        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
            ], 404);
        }

        $ambiente = Ambiente::find($request->id_ambiente);

        if (!$ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Ambiente nao encontrado'
            ], 404);
        }

        //Busca o horario disponivel daquele ambiente
        $horarioFuncionamento = HorarioFuncionamento::where('id_ambiente', $request->id_ambiente)->get();

        // Busca todas as reservas ativas no id do ambiente
        $reservas = Reservas::where('statusReserva', 1)
            ->where('id_ambiente', $request->id_ambiente)
            ->pluck('horario')
            ->toArray();

        $horariosDisponiveis = $horarioFuncionamento->filter(function ($horarioFunciona) use ($reservas) {
            return !in_array($horarioFunciona->horario, $reservas);
        });


        return response()->json([
            'error' => false,
            'message' => 'Horarios disponiveis',
            'horario' => $horariosDisponiveis->values()
        ], 200);
    }
}
