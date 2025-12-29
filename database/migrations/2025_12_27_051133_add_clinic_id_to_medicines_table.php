<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('Medicines', function (Blueprint $table) {
            $table->unsignedInteger('ClinicId')->nullable();
            $table->foreign('ClinicId')->references('id')->on('Doctors');
        });
    }

    public function down(): void
    {
        Schema::table('Medicines', function (Blueprint $table) {
            $table->dropForeign('medicines_clinicid_foreign');
        });
    }
};
