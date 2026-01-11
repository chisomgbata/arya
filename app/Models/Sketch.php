<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sketch extends Model
{
    protected $fillable = [
        'Patient_id',
        'sketch',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'Patient_id');
    }
}
