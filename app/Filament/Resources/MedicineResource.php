<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicineResource\Pages;
use App\Models\Medicine;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static ?string $slug = "medicines";

    protected static string|null|UnitEnum $navigationGroup = "Medicine Management";

    protected static ?int $navigationSort = 2;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;


    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make("MedicineFormId")
                ->searchable()
                ->relationship("medicineForm", "Name")
                ->preload()
                ->required(),
            TextInput::make("Name"),

            TextInput::make("CompanyName"),

            RichEditor::make("Description")->columnSpanFull(),

            Checkbox::make("IsPattern"),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query): Builder {
                if (!Filament::getTenant()) {
                    return $query;
                }
                return $query->where("ClinicId", Filament::getTenant()->Id);
            })
            ->columns([
                TextColumn::make("Name")->searchable(),

                TextColumn::make("CompanyName")->searchable(),

                TextColumn::make("medicineForm.Name"),

                CheckboxColumn::make("IsPattern"),
            ])
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
            "index" => Pages\ListMedicines::route("/"),
            "create" => Pages\CreateMedicine::route("/create"),
            "edit" => Pages\EditMedicine::route("/{record}/edit"),
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
        return ["Name", "CompanyName"];
    }
}
