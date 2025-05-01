<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Extiende de Authenticatable

class Admin extends Authenticatable
{
    use HasFactory;

    // Asegúrate de que el modelo tiene los campos adecuados para autenticación
    protected $fillable = [
        'username',
        'password',
    ];

    // Definir 'username' como el identificador de autenticación
    public function getAuthIdentifierName()
    {
        return 'username';  // Devuelve el nombre del campo que usas para autenticar al admin
    }

    // Si 'username' es tu clave primaria, configúralo de esta manera
    protected $primaryKey = 'username';
}
