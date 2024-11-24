<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
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
     * Display a listing of the resource.
     */
    public function horariosAmbiente($id_ambiente)
    {
        $ambiente = Ambiente::find($id_ambiente);


        if (!$id_ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum ambiente encontrado'
            ], 404);
        }


        $horario = HorarioFuncionamento::where('id_ambiente', $ambiente->id)->get();



        return response()->json([
            'error' => false,
            'message' => 'Horarios do ambiente',
            'horario' => $horario,
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
                'horario' => 'required|string',
            ],
            [
                'required' => 'O campo :attribute e obrigatorio',
                'exists' => 'O :attribute informado nao existe na tabela ambientes',
                'string' => 'O :attribute deve ser string',
            ],
            [
                'id_ambiente' => 'Id Ambiente',
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
    public function update(Request $request, $id, $id_ambiente)
    {
        $horarioFuncionamento = HorarioFuncionamento::find($id);
        $ambiente = Ambiente::find($id_ambiente);


        if (!$horarioFuncionamento || !$id_ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum ambiente ou horario encontrado'
            ], 404);
        }


        $horario = HorarioFuncionamento::where('id_ambiente', $ambiente->id)->get();


        $validator = Validator::make([
            $request->all(),
            [
                'horario' => 'required',
                'string',
                'regex:/^\d{2}:\d{2}-\d{2}:\d{2}$/',
            ],
            [
                'required' => 'O valor :attribute e obrigatorio',
                'string' => 'O valor :attribute e string',
                'regex' => 'O valor :attribute deve estar no formato HH:mm-HH:mm',
            ],
            [
                'horario' => 'Horario',
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'errors' => $validator->errors(),
            ], 422);
        }


        $horario->update([
            'horario' => $request->horario
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Horarios do ambiente',
            'horario' => $horario,
        ], 200);
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
