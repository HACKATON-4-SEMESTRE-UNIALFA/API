<?php

namespace App\Http\Controllers;

use App\Models\WhiteList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WhiteListController extends Controller
{
    /**
     * Lista as datas da white list
     */
    public function index()
    {
        $whiteList = WhiteList::all();

        if (!$whiteList) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhuma data na white list encontrada',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'whiteList' => $whiteList
        ], 200);
    }

    /**
     * cria datas para a white list.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d',
        ], [
            'required' => 'O campo :attribute e obrigatorio',
            'date_format' => 'O :attribute deve estar no formato correto: :format.',
        ], [
            'data' => 'Data',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'errors' => $validator->errors(),
            ], 422);
        }

        $whiteList = WhiteList::create($request->all());

        if (!$whiteList) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao criar data na white list',
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'Data criada com sucesso',
            'whiteList' => $whiteList
        ], 200);
    }

    /**
     * Visualiza uma unica data salva na white list
     */
    public function show($id)
    {
        $whiteList = WhiteList::find($id);

        if (!$whiteList) {
            return response()->json([
                'error' => true,
                'message' => 'Data nao encontrada na white list',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'whiteList' => $whiteList
        ], 200);
    }

    /**
     * Atualiza a data da white list selecionada
     */
    public function update(Request $request, $id)
    {
        $whiteList = WhiteList::find($id);

        if (!$whiteList) {
            return response()->json([
                'error' => true,
                'message' => 'Data nao encontrada na white list',
            ], 404);
        }

        $whiteList->update($request->all());

        return response()->json([
            'error' => false,
            'message' => 'Data atualizada com sucesso',
            'whiteList' => $whiteList
        ], 200);
    }

    /**
     * Deleta a data da white list
     */
    public function destroy($id)
    {
        $whiteList = WhiteList::find($id);

        if (!$whiteList) {
            return response()->json([
                'error' => true,
                'message' => 'Data nao encontrada na white list',
            ], 404);
        }

        $whiteList->delete();
    }
}
