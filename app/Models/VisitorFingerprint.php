<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorFingerprint extends Model
{
    protected $fillable = ['visitor_id', 'fingerprint_template', 'finger_type'];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
