<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiseaseResource\Pages;
use App\Filament\Resources\DiseaseResource\RelationManagers\LaboratoryReportsRelationManager;
use App\Filament\Resources\DiseaseResource\RelationManagers\SymptomsRelationManager;
use App\Models\Disease;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiseaseResource extends Resource
{
    protected static ?string $model = Disease::class;

    protected static ?string $slug = "diseases";

    protected static ?string $navigationGroup = "Disease Management";

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlineVirus;

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        return $record->Name;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make("Name"),

            RichEditor::make("Introduction"),

            RichEditor::make("Purvaroopa"),

            RichEditor::make("DoDont"),

            RichEditor::make("Sadhyabadyatva"),

            RichEditor::make("ChikitsaSutra"),

            TextInput::make("Samprapti"),

            TextInput::make("Upadrava"),

            TextInput::make("Panchakarma"),

            TextInput::make("Causes"),

            TextInput::make("ArishtaLaxana"),

            TextInput::make("DifferentialDiagnosis"),

            TextInput::make("LaboratoryInvestions"),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([TextColumn::make("Name")->searchable()])
            ->filters([TrashedFilter::make()])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\\ListDiseases::route("/"),
            "create" => Pages\\CreateDisease::route("/create"),
            "edit" => Pages\\EditDisease::route("/{record}/edit"),
        ];
    }

    public static function getRelations(): array
    {
        return [
            SymptomsRelationManager::class,
            LaboratoryReportsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ["Name"];
    }
}
