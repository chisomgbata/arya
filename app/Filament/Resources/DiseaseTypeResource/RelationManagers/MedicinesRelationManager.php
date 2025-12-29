<?php

namespace App\Filament\Resources\DiseaseTypeResource\RelationManagers;

use App\Filament\Resources\MedicineResource;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MedicinesRelationManager extends RelationManager
{
    protected static string $relationship = 'medicines';

    protected static ?string $relatedResource = MedicineResource::class;

    protected static string|null|BackedEnum $icon = Heroicon::OutlinedQueueList;

    protected static ?string $label = 'Medicines';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
