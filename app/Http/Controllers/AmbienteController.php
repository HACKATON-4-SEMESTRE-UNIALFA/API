<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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


    public function showImage($dirname, $filename)
    {

        $filePath = "{$dirname}/{$filename}";

        if (Storage::disk('public')->exists($filePath)) {
            return response()->file(Storage::disk('public')->path($filePath));
        }

        return response()->json(['error' => 'Imagem não encontrada'], 404);
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
                'status' => 'required|in:disponivel,indisponivel,manutencao',
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
    public function update(Request $request, $id)
    {
        // Encontre o ambiente pelo ID
        $ambiente = Ambiente::find($id);

        if (!$ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Ambiente não encontrado'
            ], 404);
        }

        // Validação dos dados recebidos
        $validator = Validator::make(
            $request->all(),
            [
                'nome' => 'required|string',
                'capacidade' => 'required|string',
                'status' => 'required|in:disponivel,indisponivel,manutencao',
                'equipamentos_disponiveis' => 'required|string',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480', // Imagem opcional
            ],
            [
                'required' => 'O campo :attribute é obrigatório!',
                'string' => 'O campo :attribute deve ser uma string!',
            ],
            [
                'nome' => 'Nome',
                'capacidade' => 'Capacidade',
                'status' => 'Status',
                'equipamentos_disponiveis' => 'Equipamentos Disponíveis',
                'imagem' => 'Imagem',
            ]
        );

        // Se houver erros de validação
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validação dos dados',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Atualiza os dados do ambiente
            $ambiente->nome = $request->input('nome');
            $ambiente->capacidade = $request->input('capacidade');
            $ambiente->status = $request->input('status');
            $ambiente->equipamentos_disponiveis = $request->input('equipamentos_disponiveis');

            // Se uma nova imagem for enviada
            if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
                // Deleta a imagem antiga, se existir
                if ($ambiente->imagem) {
                    Storage::disk('public')->delete('imagens/' . $ambiente->imagem);
                }

                // Armazena a nova imagem
                $path = $request->file('imagem')->store('imagens', 'public');
                $nomeArquivo = basename($path);

                // Atualiza o campo de imagem
                $ambiente->imagem = $nomeArquivo;
            }

            // Salva as alterações no banco de dados
            $ambiente->save();

            return response()->json([
                'error' => false,
                'message' => 'Ambiente atualizado com sucesso!',
                'ambiente' => $ambiente,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao atualizar o ambiente',
                'erro' => $e->getMessage()
            ], 500);
        }
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
