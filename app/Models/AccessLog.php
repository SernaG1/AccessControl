<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UsersIncome;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',  // Relaciona con el visitante
        'entry_time',  // Hora de entrada
        'exit_time',   // Hora de salida
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    /**
     * Relación: Un AccessLog pertenece a un visitante (UsersIncome).
     */
    public function visitor()
    {
        return $this->belongsTo(UsersIncome::class, 'visitor_id');
    }
}

