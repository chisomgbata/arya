<?php

namespace App\Filament\Resources\DiseaseTypeResource\RelationManagers;

use App\Filament\Resources\MedicineResource;
use App\Models\Medicine;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
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
                Action::make('attachMedicines')
                    ->label('Attach Medicines')
                    ->icon(Heroicon::OutlinedPaperClip)
                    ->form([
                        CheckboxList::make('medicines')
                            ->options(fn () => Medicine::query()->pluck('Name', 'Id'))
                            ->default(fn () => $this->getOwnerRecord()->medicines()->pluck('Medicines.Id')->toArray())
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2),
                    ])
                    ->modalWidth('3xl')
                    ->stickyModalFooter()
                    ->action(function (array $data): void {
                        $this->getOwnerRecord()->medicines()->sync($data['medicines']);
                    }),
            ])->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ]);
    }
}
