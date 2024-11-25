<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Ambiente;
use App\Models\HistoricoReserva;
use App\Models\Reservas;
use App\Models\Usuario;
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


    public function showImage($filename)
    {

        $filePath = "/imagens/{$filename}";

        if (Storage::disk('public')->exists($filePath)) {
            return response()->file(Storage::disk('public')->path($filePath));
        }

        return response()->json(['error' => 'Imagem não encontrada'], 404);
    }


    /**
     * Cria um novo ambiente
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome' => 'required|string',
                'capacidade' => 'required|string',
                'equipamentos_disponiveis' => 'required|string',
                'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
                'status' => 'required|in:Disponível,Indisponível,Manutenção',
            ],
            [
                'required' => 'O campo :attribute e obrigatorio!',
                'string' => 'O campo :attribute e string!',
                'in' => 'O campo :attribute precisa ser Disponível,Indisponível ou Manutenção'
            ],
            [
                'nome' => 'Nome',
                'capacidade' => 'capacidade',
                'equipamentos_disponiveis' => 'equipamentos_disponiveis',
                'imagem' => 'imagem',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao dos dados',
                'error' => $validator->errors()
            ], 200);
        }

        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

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

    public function update(Request $request, $id, $id_alteracao)
    {

        $ambiente = Ambiente::find($id);

        if (!$ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Ambiente não encontrado'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nome' => 'required|string',
                'capacidade' => 'required|string',
                'status' => 'required|in:Disponível,Indisponível,Manutenção',
                'equipamentos_disponiveis' => 'required|string',
                'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
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


        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validação dos dados',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ambiente->nome = $request->input('nome');
            $ambiente->capacidade = $request->input('capacidade');
            $ambiente->status = $request->input('status');
            $ambiente->equipamentos_disponiveis = $request->input('equipamentos_disponiveis');

            if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

                $path = $request->file('imagem')->store('imagens', 'public');
                $nomeArquivo = basename($path);

                $ambiente->update([
                    'imagem' => $nomeArquivo
                ]);
            }

            $ambiente->save();

            $ambienteStatus = $ambiente->status;

            if ($ambienteStatus !== 'Disponível') {

                $reservas = Reservas::where('id_ambiente', $ambiente->id)
                    ->where('status', 'Ativo')
                    ->get();

                if (!$reservas->isEmpty()) {

                    $usuarioAlteracao = Usuario::find($request->id_alteracao);

                    foreach ($reservas as $reserva) {
                        $reserva->status = 'Cancelado';
                        $reserva->save();

                        HistoricoReserva::create([
                            'id_reserva' => $reserva->id,
                            'id_alteracao' => $usuarioAlteracao->id,
                            'id_usuario' => $reserva->id_usuario,
                            'id_ambiente' => $reserva->id_ambiente,
                            'horario' => $reserva->horario,
                            'data' => $reserva->data,
                            'status' => 'Cancelado'
                        ]);

                        $tipo = 'Cancelado';
                        $mensagem = 'Reserva cancelada porque o ambiente ' . $ambiente->nome . ' está desativado.';
                        NotificacaoController::store($reserva, $reserva->id_usuario, $tipo, $mensagem);
                    }
                }
            }

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
     * Lista todos os ambientes disponíveis
     */
    public function EnableAll()
    {
        $ambientes = Ambiente::where('status', 'Disponível')->get();

        if ($ambientes->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum ambiente disponível encontrado!',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Ambientes disponíveis encontrados com sucesso!',
            'ambientes' => $ambientes
        ], 200);
    }
}
