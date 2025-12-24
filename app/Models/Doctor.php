<?php

namespace App\Models;

use App\Traits\AuditFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doctor extends Model
{
    use AuditFields;

    protected $table = 'Doctors';

    protected function casts(): array
    {
        return [
            'IsDeleted' => 'boolean',
        ];
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'DoctorUsers', 'DoctorId', 'UserId')->withPivot('role');
    }
}
