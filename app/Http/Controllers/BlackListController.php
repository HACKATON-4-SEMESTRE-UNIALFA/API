<?php

namespace App\Http\Controllers;

use App\Models\BlackList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;

class BlackListController extends Controller
{
      /**
     * Lista as datas da black list
     */
    public function index()
    {
        $blackList = BlackList::all();

        if (!$blackList) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhuma data na black list encontrada',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'blackList' => $blackList
        ], 200);
    }

    /**
     * cria datas para a black list.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
        ], [
        ], [
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'errors' => $validator->errors(),
            ], 422);
        }

        $blackList = BlackList::create($request->all());

        if (!$blackList) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao criar data na black list',
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'Data criada com sucesso',
            'blackList' => $blackList
        ], 200);
    }

    /**
     * Visualiza uma unica data salva na black list
     */
    public function show($id)
    {
        $blackList = BlackList::find($id);

        if (!$blackList) {
            return response()->json([
                'error' => true,
                'message' => 'Data nao encontrada na white list',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'whiteList' => $blackList
        ], 200);
    }

    /**
     * Atualiza a data da black list selecionada
     */
    public function update(Request $request, $id)
    {
        $blackList = BlackList::find($id);

        if (!$blackList) {
            return response()->json([
                'error' => true,
                'message' => 'Data nao encontrada na white list',
            ], 404);
        }

        $blackList->update($request->all());

        return response()->json([
            'error' => false,
            'message' => 'Data atualizada com sucesso',
            'blackList' => $blackList
        ], 200);
    }

    /**
     * Deleta a data da black list
     */
    public function destroy($id)
    {
        $blackList = BlackList::find($id);

        if (!$blackList) {
            return response()->json([
                'error' => true,
                'message' => 'Data nao encontrada na white list',
            ], 404);
        }

        $blackList->delete();
    }
}
