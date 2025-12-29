<?php

namespace App\Filament\App\Resources\Patients\Resources\PatientHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PatientHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('diseases.Name')->limitList(3)->badge(),
                TextColumn::make('symptoms.Name')->limitList(3)->badge(),
                TextColumn::make('prescriptions_count')->counts('prescriptions')->label('Medicines Count'),
                TextColumn::make('ConsultationFee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('MedicinesFee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('CreatedDate')
                    ->dateTime()->label('Appointment Date')
                    ->sortable(),
                TextColumn::make('NextAppointmentDate')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
