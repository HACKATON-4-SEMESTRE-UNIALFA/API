<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_usuario',
        'id_ambiente',
        'horario',
        'dia',
        'statusReserva',
    ];

    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class);
    }
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
    
}
