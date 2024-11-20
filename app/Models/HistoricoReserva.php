<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoReserva extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_reserva',
        'ambienteAnterior',
        'horarioAnterior',
        'dataAnterior',
        'statusAnterior',
    ];


    public function reserva()
    {
        return $this->belongsTo(Reservas::class);
    }
}
