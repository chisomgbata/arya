<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\Patients\PatientResource;
use App\Models\PatientHistory;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Illuminate\Database\Eloquent\Builder;

class Calendar extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;


    /**
     * fetch events from the database
     */
    public function getEvents($info): Builder
    {
        return PatientHistory::query()
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
}
