<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Reservas;
use Illuminate\Http\Request;

class Relatorio extends Controller
{
    public function showRelatorioAmbienteStatus()
    {
        $ambientes_disponivel = Ambiente::where('status', 'Disponível')->count();
        $ambientes_indisponivel = Ambiente::where('status', 'Indisponível')->count();
        $ambientes_manutencao = Ambiente::where('status', 'Manutenção')->count();


        return response()->json([
            'error' => false,
            'message' => 'Relatório de status dos ambientes gerado com sucesso',
            'Disponível' => $ambientes_disponivel,
            'Indisponível' => $ambientes_indisponivel,
            'Manutenção' => $ambientes_manutencao,
        ], 200);
    }


    public function showRelatorioReservas()
    {
        $reservas = Reservas::where('status', 'ativo')->count();
        $reservas_confirmadas = Reservas::where('status', 'confirmada')->count();
        $reservas_canceladas = Reservas::where('status', 'cancelado')->count();

        return response()->json([
            'error' => false,
            'message' => 'Relatório de reservas gerado com sucesso',
            'Ativas' => $reservas,
            'Confirmado' => $reservas_confirmadas,
            'Cancelado' => $reservas_canceladas,
        ], 200);
    }

}