<?php

namespace App\Filament\Resources\DiseaseTypeResource\Pages;

use App\Filament\Resources\DiseaseTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditDiseaseType extends EditRecord
{
    protected static string $resource = DiseaseTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
