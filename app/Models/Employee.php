<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_documento',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'genero',
        'rh',
        'telefono',
        'nombre_contacto_emergencia',
        'telefono_contacto_emergencia',
        'direccion',
        'area',
        'foto_webcam',
        'estado',
        'fingerprint_data',
        'fingerprint_template'
    ];

    public function accessLogs()
    {
        return $this->hasMany(\App\Models\EmployeeAccessLog::class, 'employee_id');
    }
        public function fingerprints()
    {
        return $this->hasMany(EmployeeFingerprint::class);
    }
}
