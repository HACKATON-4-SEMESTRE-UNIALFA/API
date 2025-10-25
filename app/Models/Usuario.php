<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'password',
        'telefone',
        'cpf',
        'isAdmin',
        'isUser',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the JWT token.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
