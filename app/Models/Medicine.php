<?php

namespace App\Models;

use App\Traits\AuditFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicine extends Model
{
    use AuditFields;

    protected $table = 'Medicines';

    public function medicineForm(): BelongsTo
    {
        return $this->belongsTo(MedicineForm::class, 'MedicineFormId');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'ClinicId');
    }

    protected function casts(): array
    {
        return [
            'IsPattern' => 'boolean',
            'IsSpecial' => 'boolean',
        ];
    }
}
