<?php

namespace App\Models;

use App\Traits\AuditFields;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function prakruti(): HasOne
    {
        return $this->hasOne(PatientPrakruti::class, 'PatientId');
    }

    protected function casts(): array
    {
        return [
            'IsDeleted' => 'boolean',
        ];
    }
}
