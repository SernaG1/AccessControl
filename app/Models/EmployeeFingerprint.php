<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFingerprint extends Model
{
    protected $fillable = ['employee_id', 'fingerprint_template', 'finger_type'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
