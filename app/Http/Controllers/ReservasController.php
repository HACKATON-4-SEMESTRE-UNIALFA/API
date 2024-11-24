<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\HistoricoReserva;
use App\Models\HorarioFuncionamento;
use App\Models\Notificacao;
use App\Models\Reservas;
use App\Models\Usuario;
use Faker\Core\Number;
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

        $reserva = Reservas::create([
            'id_usuario' => $request->input('id_usuario'),
            'id_ambiente' => $request->input('id_ambiente'),
            'horario' => $request->input('horario'),
            'data' => $request->input('data'),
            'status' => 'ativo'
        ]);


        $historicoOk = HistoricoReserva::create([
            'id_usuario' => $request->input('id_usuario'),
            'id_ambiente' => $request->input('id_ambiente'),
            'id_reserva' => $reserva->id,
            'id_alteracao' => $request->input('id_usuario'),
            'horario' => $request->input('horario'),
            'data' => $request->input('data'),
            'status' => 'ativo'
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
                'id_ambiente' => 'required|exists:ambientes,id',
                'id_alteracao' => 'required|integer',
                'horario' => 'required|string',
                'data' => 'required|date_format:Y-m-d',
            ],
            [
                'required' => 'O campo :attribute e obrigatorio',
                'id_ambientes.exists' => 'O :attribute informado nao existe na tabela ambientes',
                'date_format' => 'O :attribute deve estar no formato correto: :format.',
                'string' => 'O :attribute deve ser string',
            ],
            [
                'id_ambiente' => 'Id Ambiente',
                'data' => 'data',
                'horario' => 'Horario',
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

        $usuarioAlteracao = Usuario::find($request->id_alteracao);
        if (!$usuarioAlteracao) {
            return response()->json([
                'error' => true,
                'message' => 'Não foi possivel encontrar usuario que esta solicitando alteração'
            ], 500);
        }

        $tipo = 'Alteração';
        $mensagem = 'Reserva alterada por ' . $usuarioAlteracao->nome;

        $reservaAtual = Reservas::find($id);

        if (!$reservaAtual) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhuma reserva encontrada',
            ]);
        }


        $notificaAlteracao = NotificacaoController::store($reservaAtual, $reservaAtual->id_usuario, $tipo, $mensagem);

        $historico = HistoricoReserva::create([
            'id_reserva' => $reservaAtual->id,
            'id_alteracao' => $request->id_alteracao,
            'id_usuario' => $reservaAtual->id_usuario,
            'id_ambiente' => $request->input('id_ambiente'),
            'horario' => $request->input('horario'),
            'data' => $request->input('data'),
            'status' => $reservaAtual->status
        ]);


        $reservaAtual->update([
            'id_usuario' => $reservaAtual->id_usuario,
            'id_ambiente' => $request->id_ambiente,
            'horario' => $request->horario,
            'data' => $request->data,
            'status' => $reservaAtual->status
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Reserva editada com sucesso!',
            'reserva' => $reservaAtual,
            'historico' => $historico,
            'notificacao' => $notificaAlteracao,
        ], 201);
    }

    /**
     * Desabilita o status da deserva (altera para cancelado)
     */
    public function desable(Request $request, $id, $id_alteracao)
    {

        $reserva = Reservas::find($id);

        if (!$reserva) {
            return response()->json([
                'error' => true,
                'message' => 'Nenhum reserva encontrada'
            ], 404);
        }

        //Tratar request
        $validator = Validator::make(
            $request->all(),
            [
                'mensagem' => 'required|string|max:100',
            ],
            [
                'required' => 'O campo :attribute e obrigatorio',
                'strin' => 'O campo :attribute e string',
                'max' => 'O campo :attribute deve conter no maximo 100 caracteres',
            ],
            [
                'mensagem' => 'Mensagem',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Erro na validacao de dados',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tipo = 'Cancelado';

        $notificaAlteracao = NotificacaoController::store($reserva, $reserva->id_usuario, $tipo, $request->mensagem);

        $historico = HistoricoReserva::create([
            'id_reserva' => $reserva->id,
            'id_alteracao' => $id_alteracao,
            'id_usuario' => $reserva->input('id_usuario'),
            'id_ambiente' => $reserva->input('id_ambiente'),
            'horario' => $reserva->input('horario'),
            'data' => $reserva->input('data'),
            'status' => 'cancelado'
        ]);

        $reserva->update([
            'status' => 'cancelado'
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Reserva cancelada',
            'reserva' => $reserva,
            'notificacao' => $notificaAlteracao,
            'reserva' => $reserva,
            'historico' => $historico
        ], 200);
    }


    /**
     * Retorna os dias que estao com todos os horarios de funcionamento preenchidos
     */
    public function verificaReservaDia($id)
    {
        $idInt = (int)$id;
        $ambiente = Ambiente::find($idInt);

        if (!$ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Ambiente nao encontrado'
            ], 404);
        }

        $horariosFuncionamento = HorarioFuncionamento::where('id_ambiente', $idInt)
            ->pluck('horario')
            ->toArray();


        $reservas = Reservas::where('id_ambiente', $idInt)
            ->where('status', 'ativo')
            ->get();

        $reservasPorData = $reservas->groupBy('data');

        $diasCompletos = [];
        foreach ($reservasPorData as $data => $reservasDoDia) {
            $horariosReservados = $reservasDoDia->pluck('horario')->toArray();
            if (empty(array_diff($horariosFuncionamento, $horariosReservados))) {
                $diasCompletos[] = $data;
            }
        }

        if (!$diasCompletos) {
            return response()->json([
                'Todos os dias estao liberados'
            ]);
        }

        return response()->json([
            'idAmbiente' => $ambiente->id,
            'nomeAmbiente' => $ambiente->nome,
            'diasOcupados' => $diasCompletos,
        ]);
    }

    /**
     * Retorna os horarios de funcionamento ocupados de dias parcialmente preenchidos
     */
    public function verificaReservaHorario($id, $data)
    {
        $idInt = (int)$id;
        $ambiente = Ambiente::find($idInt);

        if (!$ambiente) {
            return response()->json([
                'error' => true,
                'message' => 'Ambiente não encontrado'
            ], 404);
        }

        $horariosFuncionamento = HorarioFuncionamento::where('id_ambiente', $idInt)
            ->pluck('horario')
            ->toArray();

        $reservasDoDia = Reservas::where('id_ambiente', $idInt)
            ->where('status', 'ativo')
            ->where('data', $data)
            ->pluck('horario')
            ->toArray();

        $horariosDisponiveis = array_diff($horariosFuncionamento, $reservasDoDia);

        if (empty($horariosDisponiveis)) {
            return response()->json([
                'Todos os horários estão ocupados para esta data'
            ]);
        }

        return response()->json([
            'nomeAmbiente' => $ambiente->nome,
            'data' => $data,
            'horariosDisponiveis' => $horariosDisponiveis,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function showUserReserva($id)
    {
        $reservas = Reservas::where('id_usuario', $id);

        if (!$reservas) {
            return response()->json([
                'error' => true,
                'message' => 'Historico de alteracao nao encontrado',
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Historico de alteracao listado com sucesso',
            'historico' => $reservas,
        ], 200);
    }
}
