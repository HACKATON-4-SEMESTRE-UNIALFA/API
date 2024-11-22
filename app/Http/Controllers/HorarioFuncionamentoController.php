<?php

namespace App\Http\Controllers;

use App\Models\HorarioFuncionamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioFuncionamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horarioFuncionamento = HorarioFuncionamento::all();
        if (!$horarioFuncionamento) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum horario encontrado!'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Horarios Listados',
            'horarios' => $horarioFuncionamento
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
                'id_ambiente' => 'required|exists:ambientes,id',
                'dia' => 'required|date_format:Y-m-d',
                'horario' => 'required|string',
            ],
            [
                'required' => 'O campo :attribute e obrigatorio',
                'exists' => 'O :attribute informado nao existe na tabela ambientes',
                'date_format' => 'O :attribute deve estar no formato correto: :format.',
                'string' => 'O :attribute deve ser string',
            ],
            [
                'id_ambiente' => 'Id Ambiente',
                'dia' => 'Dia',
                'horario' => 'Horario',
            ],
            422
        );


        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'errors' => $validator->errors(),
            ], 422);
        }

        $horarioFuncionamento = HorarioFuncionamento::create([
            'id_ambiente' => $request->input('id_ambiente'),
            'dia' => $request->input('dia'),
            'horario' => $request->input('horario'),
        ]);


        return response()->json([
            'error' => false,
            'message' => 'Horario de funcionamento criado com sucesso',
            'horario' => $horarioFuncionamento,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $horarioFuncionamento = HorarioFuncionamento::find($id);

        if (!$horarioFuncionamento) {
            return response()->json([
                'error' => true,
                'message' => 'Horario nao encontrado'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Horario encontrado',
            'horario' => $horarioFuncionamento
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HorarioFuncionamento $horarioFuncionamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $horarioFuncionamento = HorarioFuncionamento::find($id);

        if (!$horarioFuncionamento) {
            return response()->json([
                'error' => true,
                'message' => 'Horario nao encontrado',
            ], 404);
        }

        $horarioFuncionamento->delete($id);

        return response()->json([
            'error' => false,
            'message' => 'Horario deletado',
            'horario' => $horarioFuncionamento
        ], 200);
    }
}
