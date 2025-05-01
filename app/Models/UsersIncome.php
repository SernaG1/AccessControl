<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AccessLog;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsersIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_documento',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'genero',
        'area',
        'rh',
        'foto_webcam',
    ];

    /**
     * Relación: Un visitante (UsersIncome) tiene muchos registros de acceso (AccessLog).
     */
    public function accessLogs()
    {
        // Relaciona a AccessLog con 'visitor_id' en lugar de 'users_income_id'
        return $this->hasMany(AccessLog::class, 'visitor_id');
    }
}
