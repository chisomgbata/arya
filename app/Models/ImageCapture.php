<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImageCapture extends Model
{
    protected $fillable = [
        'capture',
    ];

    public function patientHistory(): BelongsTo
    {
        return $this->belongsTo(PatientHistory::class);
    }
}
