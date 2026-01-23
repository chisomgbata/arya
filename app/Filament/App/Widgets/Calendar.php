<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\Patients\PatientResource;
use App\Models\PatientHistory;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Calendar extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    protected bool $eventClickEnabled = true;
    protected bool $dateClickEnabled = true;


    public function visitPatientAction(): Action
    {
        return Action::make('visitPatient')
            ->model(PatientHistory::class)
            ->action(function (PatientHistory $record) {
                $tenant = Filament::getTenant();
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
        $this->mountAction('visitPatient');

    }

    protected function getEvents(FetchInfo $info): Collection|array|Builder
    {
        return [];
    }
}
