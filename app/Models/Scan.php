<?php

// app/Models/Scan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Scan extends Model
{
    // Desactiva timestamps (created_at, updated_at)
    public $timestamps = false;

    // Desactiva la tabla asociada
    protected $table = null;

    // Permite llenar atributos dinámicamente
    protected $guarded = [];

    // Opcional: puedes desactivar completamente el guardado
    public function save(array $options = [])
    {
        // no hace nada
    }
}
