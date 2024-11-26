<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reserva',
        'id_usuario',
        'infoReserva',
        'tipo',
        'mensagem',
        'visualizacao',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reservas::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}