<?php

use App\Models\PatientHistory;

Route::get('/orders/{history}/print', function (PatientHistory $history) {
    $history->load(['patient', 'prescriptions.medicine', 'diseases', 'clinic']);
    return view('print.history', ['history' => $history, 'clinic' => $history->clinic, 'patient' => $history->patient]);
})->name('order.print');

