<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\Patients\PatientResource;
use App\Models\CalendarAppointment;
use App\Models\PatientHistory;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\Filament\Actions\DeleteAction;
use Guava\Calendar\Filament\Actions\EditAction;
use Guava\Calendar\Filament\Actions\ViewAction;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Calendar extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    protected bool $eventClickEnabled = true;

    protected bool $dateClickEnabled = true;

    protected function getEvents(FetchInfo $info): Collection|array
    {
        $tenantId = Filament::getTenant()?->Id;

        $appointments = CalendarAppointment::query()
            ->where('ClinicId', $tenantId)
            ->where('StartDate', '>=', $info->start)
            ->where('StartDate', '<=', $info->end)
            ->get();

        $patientHistories = PatientHistory::query()
            ->whereHas('patient', fn ($query) => $query->where('ClinicId', $tenantId))
            ->whereNotNull('NextAppointmentDate')
            ->where('NextAppointmentDate', '>=', $info->start)
            ->where('NextAppointmentDate', '<=', $info->end)
            ->with('patient')
            ->get();

        return $appointments->merge($patientHistories);
    }

    public function calendarAppointmentSchema(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('Title')
                ->required()
                ->maxLength(450),
            Textarea::make('Description')
                ->rows(3),
            DateTimePicker::make('StartDate')
                ->required()
                ->label('Start Date'),
            DateTimePicker::make('EndDate')
                ->label('End Date'),
            Checkbox::make('AllDay')
                ->label('All Day')
                ->default(true),
        ]);
    }

    protected function onDateClick(DateClickInfo $info): void
    {
        $this->mountAction('createCalendarAppointment');
    }

    protected function onEventClick(EventClickInfo $info, Model $event, ?string $action = null): void
    {
        if ($event instanceof PatientHistory) {
            $this->mountAction('visitPatient');

            return;
        }

        if ($action) {
            $this->mountAction($action);
        }
    }

    public function createCalendarAppointmentAction(): CreateAction
    {
        return $this->createAction(CalendarAppointment::class)
            ->mutateFormDataUsing(function (array $data): array {
                $data['ClinicId'] = Filament::getTenant()?->Id;

                return $data;
            })
            ->fillForm(function (?DateClickInfo $info): array {
                return [
                    'StartDate' => $info?->date?->toDateTimeString(),
                    'AllDay' => $info?->allDay ?? true,
                ];
            });
    }

    public function editAction(): EditAction
    {
        return parent::editAction();
    }

    public function viewAction(): ViewAction
    {
        return parent::viewAction();
    }

    public function deleteAction(): DeleteAction
    {
        return parent::deleteAction()
            ->requiresConfirmation();
    }

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
                        tenant: $tenant,
                    )
                );
            });
    }

    protected function getEventClickContextMenuActions(): array
    {
        return [
            $this->editAction(),
            $this->viewAction(),
            $this->deleteAction(),
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            $this->createAction(CalendarAppointment::class, 'createCalendarAppointmentHeader')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['ClinicId'] = Filament::getTenant()?->Id;

                    return $data;
                })
                ->label('New Appointment'),
        ];
    }
}
