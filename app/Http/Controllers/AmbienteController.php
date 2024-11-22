<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmbienteController extends Controller
{
    /**
     * Retorna os ambientes cadastrados no banco.
     */
    public function index()
    {
        $ambiente = Ambiente::all();
        if ($ambiente->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum ambiente encontrado!',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'ambiente' => $ambiente
        ], 200);
    }

    /**
     * Cria um novo ambiente
     */
    public function store(Request $request) //Adicionar parametro de imagem
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome' => 'required|string',
                'capacidade' => 'required|string',
                'status' => 'required|string',
                'equipamentos_disponiveis' => 'required|string',
                'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            ],
            [
                'required' => 'O campo :attribute e obrigatorio!',
                'string' => 'O campo :attribute e string!',
            ],
            [
                'nome' => 'Nome',
                'capacidade' => 'capacidade',
                'status' => 'status',
                'equipamentos_disponiveis' => 'equipamentos_disponiveis',
                'imagem' => 'imagem',
            ],
            422
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'error' => $validator->error()
            ], 422);
        }

        // Verifica se o arquivo foi enviado corretamente
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            // Armazena o arquivo
            $path = $request->file('imagem')->store('imagens', 'public');
            $nomeArquivo = basename($path);

            try {

                $ambiente = Ambiente::create([
                    'nome' => $request->input('nome'),
                    'capacidade' => $request->input('capacidade'),
                    'status' => $request->input('status'),
                    'equipamentos_disponiveis' => $request->input('equipamentos_disponiveis'),
                    'imagem' =>  $nomeArquivo,
                ], 201);

                return response()->json([
                    'error' => false,
                    'message' => 'Ambiente cadastrado com sucesso!',
                    
                    'ambiente' => $ambiente,
                 ], 201);
            
            } catch (\Exception $e) {
                return response()->json([
                    'mensagem' => 'Erro ao salvar no banco de dados',
                    'erro' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'error' => true,
            'mensagem' => 'Falha ao enviar arquivo'
        ], 500);
    }

    /**
     * Lista ambiente por id
     */
    public function show($id)
    {
        $ambiente = Ambiente::find($id);

        if (!$ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Ambiente nao encontrado!'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'ambiente' => $ambiente
        ], 200);
    }

    /**
     * Edita o ambiente por id
     */
    public function update(Request $request, $id) //Adicionar parametro de imagem
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome' => 'required|string',
                'descricao' => 'required|string'
            ],
            [
                'required' => 'O campo :attribute e obrigatorio!',
                'string' => 'O campo :attribute e string!',
            ],
            [
                'nome' => 'Nome',
                'descricao' => 'Descricao',
            ],
            422
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'error' => $validator->error()
            ], 422);
        }

        $ambiente = Ambiente::find($id);
        if (!$ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Ambiente nao encontrado',
            ], 404);
        }

        $ambiente->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Ambiente editado com sucesso!',
            'ambiente' => $ambiente
        ], 200);
    }

    /**
     * Deleta usuario por id
     */
    public function destroy($id)
    {
        $ambiente = Ambiente::find($id);
        if (!$ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Ambiente nao encontrado!',
            ], 404);
        }

        $ambiente->delete();
        return response()->json([
            'error' => false,
            'message' => 'Ambiente nao encontrado',
            'ambiente' => $ambiente
        ], 200);
    }
}
