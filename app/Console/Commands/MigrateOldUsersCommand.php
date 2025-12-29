<?php

namespace App\Console\Commands;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateOldUsersCommand extends Command
{
    protected $signature = 'migrate:old-users';

    protected $description = 'Command description';

    public function handle(): void
    {
        $patients = Patient::query()->chunk(50, function ($patients) {
            foreach ($patients as $patient) {
                $patient->update([
                    'ClinicId' => Clinic::where('MobileNo', User::find($patient->DoctorUserId)->PhoneNumber)->first()->Id ?? null
                ]);
            }
        });
    }
}
