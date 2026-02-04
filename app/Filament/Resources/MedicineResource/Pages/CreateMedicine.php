<?php

namespace App\Filament\Resources\MedicineResource\Pages;

use App\Filament\Resources\MedicineResource;
use App\Models\Medicine;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateMedicine extends CreateRecord
{
    protected static string $resource = MedicineResource::class;

    protected array $medicineDetails = [];

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->medicineDetails = Arr::only($data, [
            'MedicineId',
            'MedicineFormId',
            'CompanyName',
        ]);

        unset(
            $data['DiseaseId'],
            $data['MedicineFormId'],
            $data['CompanyName']
        );

        return $data;
    }

    protected function afterCreate(): void
    {
        if (! $this->record) {
            return;
        }

        if (empty($this->medicineDetails['MedicineId'])) {
            return;
        }

        Medicine::query()
            ->where('Id', $this->medicineDetails['MedicineId'])
            ->update([
                'MedicineFormId' => $this->medicineDetails['MedicineFormId'] ?? null,
                'CompanyName' => $this->medicineDetails['CompanyName'] ?? null,
            ]);
    }
}
