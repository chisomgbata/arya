<?php

namespace App\Models;

use App\Traits\AuditFields;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarAppointment extends Model implements Eventable
{
    use AuditFields, HasUuids;

    protected $table = 'CalendarAppointments';

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'ClinicId');
    }

    public function toCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::make($this)
            ->title($this->Title)
            ->start($this->StartDate)
            ->end($this->EndDate ?? $this->StartDate)
            ->allDay($this->AllDay)
            ->action('edit');
    }

    protected function casts(): array
    {
        return [
            'StartDate' => 'datetime',
            'EndDate' => 'datetime',
            'AllDay' => 'boolean',
            'IsDeleted' => 'boolean',
        ];
    }
}
