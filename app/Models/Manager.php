<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importante para que pueda loguearse
use Illuminate\Notifications\Notifiable;

class Manager extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'managers';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'password',
        'active',
    ];

    /**
     * Los atributos que deben ocultarse al serializar.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Mutador para encriptar la contraseña automáticamente.
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

        public function getAuthIdentifierName()
    {
        return 'name'; // Devuelve el nombre del campo que usas para autenticar al manager
    }
}