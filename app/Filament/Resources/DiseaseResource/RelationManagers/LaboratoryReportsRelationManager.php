<?php

namespace App\Filament\Resources\DiseaseResource\RelationManagers;

use App\Filament\Resources\LaboratoryReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class LaboratoryReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'laboratoryReports';

    protected static ?string $relatedResource = LaboratoryReportResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
