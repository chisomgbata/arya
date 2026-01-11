<?php

namespace App\Filament\App\Resources\Patients\Resources\PatientHistories\Pages;

use App\Filament\App\Resources\Patients\Resources\PatientHistories\PatientHistoryResource;
use App\Models\PatientHistory;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPatientHistory extends EditRecord
{
    protected static string $resource = PatientHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            Action::make('print')
                ->button()
                ->color('gray')
                ->icon(Heroicon::Printer)
                ->url(fn(PatientHistory $record): string => route('order.print', $record))
                ->openUrlInNewTab(),
        ];
    }
}
