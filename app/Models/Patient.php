<?php

namespace App\Models;

use App\Traits\AuditFields;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use AuditFields, HasUuids;

    protected $table = 'Patients';

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'ClinicId');
    }

    public function patientHistories(): HasMany
    {
        return $this->hasMany(PatientHistory::class, 'PatientId');
    }

    protected function casts(): array
    {
        return [
            'IsDeleted' => 'boolean',
        ];
    }
}
