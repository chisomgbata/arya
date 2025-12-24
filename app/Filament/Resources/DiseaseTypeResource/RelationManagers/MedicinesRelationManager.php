<?php

namespace App\Filament\Resources\DiseaseTypeResource\RelationManagers;

use App\Filament\Resources\MedicineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class MedicinesRelationManager extends RelationManager
{
    protected static string $relationship = 'medicines';

    protected static ?string $relatedResource = MedicineResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
