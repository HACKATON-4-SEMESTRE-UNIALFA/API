<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioFuncionamento extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_ambiente',
        'dia',
        'horario',
    ];

    public function amiente()
    {
        return $this->belongsTo(Ambiente::class);
    }


}


