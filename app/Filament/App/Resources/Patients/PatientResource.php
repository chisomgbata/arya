<?php

namespace App\Filament\App\Resources\Patients;

use App\Filament\App\Resources\Patients\Pages\CreatePatient;
use App\Filament\App\Resources\Patients\Pages\EditPatient;
use App\Filament\App\Resources\Patients\Pages\ListPatients;
use App\Filament\App\Resources\Patients\RelationManagers\CustomTabDataRelationManager;
use App\Filament\App\Resources\Patients\RelationManagers\HistoryRelationManager;
use App\Filament\App\Resources\Patients\Schemas\PatientForm;
use App\Filament\App\Resources\Patients\Tables\PatientsTable;
use App\Models\Patient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'FirstName';

    public static function form(Schema $schema): Schema
    {
        return PatientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            HistoryRelationManager::class,
            CustomTabDataRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatients::route('/'),
            'create' => CreatePatient::route('/create'),
            'edit' => EditPatient::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
