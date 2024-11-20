<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function horarios()
    {
        return $this->hasMany(HorarioFuncionamento::class);
    }

}
