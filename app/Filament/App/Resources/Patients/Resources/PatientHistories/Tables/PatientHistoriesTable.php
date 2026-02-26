<?php

namespace App\Filament\App\Resources\Patients\Resources\PatientHistories\Tables;

use App\Filament\App\Resources\Patients\Resources\PatientHistories\PatientHistoryResource;
use App\Models\PatientHistory;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PatientHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with([
                'patient',
                'diseases',
                'symptoms',
                'prescriptions.medicine',
            ]))
            ->defaultSort('CreatedDate', 'desc')
            ->columns([
                TextColumn::make('CreatedDate')
                    ->label('Visit')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),

                ViewColumn::make('details')
                    ->label('Details')
                    ->view('filament.app.tables.patient-history-details'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('replicate')
                    ->label('Repeat')
                    ->icon('heroicon-o-document-duplicate') // Filament v3 syntax
                    ->requiresConfirmation()
                    ->action(function (PatientHistory $record) {
                        $newHistory = DB::transaction(function () use ($record) {
                            $newHistory = $record->replicate(['prescriptions_count']);
                            $newHistory->CreatedDate = now();
                            $newHistory->save();

                            foreach ($record->prescriptions as $prescription) {
                                $newHistory->prescriptions()->save($prescription->replicate());
                            }

                            $diseases = $record->diseases()->withPivot(['DiseaseTypeId'])->get()
                                ->mapWithKeys(fn ($item) => [
                                    $item->Id => ['DiseaseTypeId' => $item->pivot->DiseaseTypeId],
                                ]);
                            $newHistory->diseases()->attach($diseases);

                            $newHistory->symptoms()->attach($record->symptoms()->pluck('SymptomId'));

                            $panchakarmas = $record->panchakarmas()->withPivot(['Detail'])->get()
                                ->mapWithKeys(fn ($item) => [
                                    $item->Id => ['Detail' => $item->pivot->Detail],
                                ]);
                            $newHistory->panchakarmas()->attach($panchakarmas);

                            $relations = ['womenHistory', 'vital', 'rogaPariksa', 'hetuPariksa'];

                            foreach ($relations as $relation) {
                                if ($record->$relation) {
                                    $newHistory->$relation()->save($record->$relation->replicate());
                                }
                            }

                            foreach ($record->patientFiles as $file) {
                                $newHistory->patientFiles()->create($file->only(['File']));
                            }

                            foreach ($record->sketches as $sketch) {
                                $newHistory->sketches()->create($sketch->only(['sketch']));
                            }

                            foreach ($record->captures as $capture) {
                                $newHistory->captures()->create($capture->only(['capture']));
                            }

                            return $newHistory;
                        });

                        return redirect(PatientHistoryResource::getUrl('edit', ['record' => $newHistory, 'patient' => $newHistory->patient]));
                    }),

                Action::make('print')
                    ->button()
                    ->color('gray')
                    ->icon(Heroicon::Printer)
                    ->url(fn (PatientHistory $record): string => route('order.print', $record))
                    ->openUrlInNewTab(),
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
