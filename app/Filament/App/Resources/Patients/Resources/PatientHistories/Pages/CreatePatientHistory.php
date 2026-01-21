<?php

namespace App\Filament\App\Resources\Patients\Resources\PatientHistories\Pages;

use App\Filament\App\Resources\Patients\PatientResource;
use App\Filament\App\Resources\Patients\Resources\PatientHistories\PatientHistoryResource;
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

}
