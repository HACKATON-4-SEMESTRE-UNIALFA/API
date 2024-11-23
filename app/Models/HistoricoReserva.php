<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoReserva extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_reserva',
        'id_usuario',
        'id_ambiente',
        'id_aletacao',
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

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
