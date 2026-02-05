<?php

namespace App\Filament\Resources\MedicineResource\Pages;

use App\Filament\Resources\MedicineResource;
use App\Models\DiseaseType;
use App\Models\Medicine;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;

class EditMedicine extends EditRecord
{
    protected static string $resource = MedicineResource::class;

    protected array $medicineDetails = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = parent::mutateFormDataBeforeFill($data);

        $data['DiseaseId'] = DiseaseType::query()
            ->where('Id', $data['DiseaseTypeId'] ?? null)
            ->value('DiseaseId');

        $medicine = Medicine::query()
            ->select(['Id', 'MedicineFormId', 'CompanyName'])
            ->find($data['MedicineId'] ?? null);

        $data['MedicineFormId'] = $medicine?->MedicineFormId;
        $data['CompanyName'] = $medicine?->CompanyName;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
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
