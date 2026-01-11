<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientFile extends Model
{
    protected $fillable = [
        'Patient_id',
        'File',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'Patient_id');
    }
}
