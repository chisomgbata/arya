<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\Patients\PatientResource;
use App\Models\PatientHistory;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Calendar extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    protected bool $eventClickEnabled = true;

    /**
     * fetch events from the database
     */
    public function getEvents($info): Builder
    {

        return PatientHistory::query()
            ->where('DoctorId', Filament::getTenant()->Id)
            ->with('patient')
            ->whereNotNull('NextAppointmentDate')
            ->where('NextAppointmentDate', '>=', $info->start)
            ->where('NextAppointmentDate', '<=', $info->end);
    }

    public function visitPatientAction(): Action
    {
        return Action::make('visitPatient')
            ->model(PatientHistory::class)
            ->action(function (PatientHistory $record) {
                // Get the tenant associated with this record
                // (Assuming Patient belongsTo Tenant, or you use the current tenant)
                $tenant = Filament::getTenant();

                // If you need to link to a record belonging to a specific tenant
                // (e.g., if you are viewing a global calendar):
                // $tenant = $record->patient->tenant;

                return redirect()->to(
                    PatientResource::getUrl(
                        name: 'edit',
                        parameters: ['record' => $record->PatientId],
                        tenant: $tenant // Explicitly passing the tenant ensures the URL is correct
                    )
                );
            });
    }

    protected function onEventClick(EventClickInfo $info, Model $event, ?string $action = null): void
    {
        // Validate the data and handle the event click
        // $event contains the clicked event record
        // you can also access it via $info->record
    }
}
