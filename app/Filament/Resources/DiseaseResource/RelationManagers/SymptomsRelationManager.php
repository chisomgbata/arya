<?php

namespace App\Filament\Resources\DiseaseResource\RelationManagers;

use App\Filament\Resources\SymptomResource;
use BackedEnum;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SymptomsRelationManager extends RelationManager
{
    protected static string $relationship = 'symptoms';

    protected static string|BackedEnum|null $icon = Heroicon::OutlinedFaceSmile;

    protected static ?string $label = 'Symptoms';

    protected static ?string $relatedResource = SymptomResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                AttachAction::make(),
            ])->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ]);
    }
}
