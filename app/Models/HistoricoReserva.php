<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoReserva extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_usuario',
        'id_ambiente',
        'horario',
        'data',
        'status',
    ];


    public function reserva()
    {
        return $this->belongsTo(Reservas::class);
    }

    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class);
    }
}
