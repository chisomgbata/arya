<?php

use App\Models\PatientFile;
use App\Models\PatientHistory;

Route::get('/orders/{history}/print', function (PatientHistory $history) {
    $history->load(['patient', 'prescriptions.medicine', 'diseases', 'clinic']);
    return view('print.history', ['history' => $history, 'clinic' => $history->clinic, 'patient' => $history->patient]);
})->name('order.print');


Route::get('/patient-files/{record}', function (PatientFile $record) {

    if (!$record->patient || !$record->patient->clinic) {
        abort(404);
    }

    $hasAccess = auth()->user()->clinics()
        ->whereKey($record->patient->clinic->Id)
        ->exists();

    if (!$hasAccess) {
        abort(403, 'Unauthorized access to this file.');
    }

    $path = $record->File;

    if (!Storage::disk('local')->exists($path)) {
        abort(404);
    }

    // 3. Return the file
    // Use 'response($path)' to view in browser (images)
    // Use 'download($path)' to force download
    return Storage::disk('local')->response($path);


})->middleware(['auth'])->name('patient.files.download');
