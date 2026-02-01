<?php

namespace App\Filament\App\Resources\Patients\Resources\PatientHistories\Pages;

use App\Filament\App\Resources\Patients\PatientResource;
use App\Filament\App\Resources\Patients\Resources\PatientHistories\PatientHistoryResource;
use App\Filament\App\Resources\Patients\Resources\PatientHistories\Widgets\PreviousHistoriesWidget;
use Filament\Resources\Pages\CreateRecord;

class CreatePatientHistory extends CreateRecord
{
    protected static string $resource = PatientHistoryResource::class;

    protected function getRedirectUrl(): string
    {
        $patientId = $this->getRecord();

        return PatientResource::getUrl('edit', [
            'record' => $patientId->PatientId,
            'relation' => '0',
        ]);
    }

    protected function getFooterWidgets(): array
    {
        return [
            PreviousHistoriesWidget::class,
        ];
    }

    public function getFooterWidgetData(): array
    {
        return [
            'patientId' => request()->route('patient'),
        ];
    }
}
