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
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PatientHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Stack 1: Clinical Details (Diseases + Symptoms)
                TextColumn::make('diseases.Name')
                    ->label('Clinical Details')
                    ->badge()
                    ->limitList(2)
                    ->separator(',')
                    ->description(fn(PatientHistory $record) => "Symptoms: " . $record->symptoms->pluck('Name')->take(3)->implode(', ')
                    )
                    ->wrap(),

                // Stack 2: Prescriptions
                TextColumn::make('prescriptions_count')
                    ->counts('prescriptions')
                    ->label('Meds')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                // Stack 3: Financials (Consultation + Medicine Fees)
                TextColumn::make('ConsultationFee')
                    ->label('Fees')
                    ->numeric()
                    ->weight(FontWeight::Bold)
                    ->prefix('Consult: ')
                    ->description(fn(PatientHistory $record) => 'Meds: ' . number_format($record->MedicinesFee)
                    )
                    ->sortable(),

                // Stack 4: Timeline (Created + Next Date)
                TextColumn::make('CreatedDate')
                    ->label('Timeline')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->description(fn(PatientHistory $record) => $record->NextAppointmentDate
                        ? 'Next: ' . $record->NextAppointmentDate->format('M d, Y')
                        : 'No follow-up'
                    ),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('replicate')
                    ->icon('heroicon-o-document-duplicate') // Filament v3 syntax
                    ->requiresConfirmation()
                    ->action(function (PatientHistory $record) {
                        $newHistory = DB::transaction(function () use ($record) {
                            $newHistory = $record->replicate(['prescriptions_count']);
                            // Optional: Reset dates on copy if needed?
                            $newHistory->CreatedDate = now();
                            $newHistory->save();

                            foreach ($record->prescriptions as $prescription) {
                                $newHistory->prescriptions()->save($prescription->replicate());
                            }

                            $diseases = $record->diseases()->withPivot(['DiseaseTypeId'])->get()
                                ->mapWithKeys(fn($item) => [
                                    $item->Id => ['DiseaseTypeId' => $item->pivot->DiseaseTypeId]
                                ]);
                            $newHistory->diseases()->attach($diseases);

                            $newHistory->symptoms()->attach($record->symptoms()->pluck('SymptomId'));

                            $panchakarmas = $record->panchakarmas()->withPivot(['Detail'])->get()
                                ->mapWithKeys(fn($item) => [
                                    $item->Id => ['Detail' => $item->pivot->Detail]
                                ]);
                            $newHistory->panchakarmas()->attach($panchakarmas);

                            $relations = ['womenHistory', 'vital', 'rogaPariksa', 'hetuPariksa'];

                            foreach ($relations as $relation) {
                                if ($record->$relation) {
                                    $newHistory->$relation()->save($record->$relation->replicate());
                                }
                            }

                            return $newHistory;
                        });

                        return redirect(PatientHistoryResource::getUrl('edit', ['record' => $newHistory, 'patient' => $newHistory->patient]));
                    }),

                Action::make('print')
                    ->button()
                    ->color('gray')
                    ->icon(Heroicon::Printer)
                    ->url(fn(PatientHistory $record): string => route('order.print', $record))
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
