<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('Medicines', function (Blueprint $table) {
            $table->foreign(['MedicineFormId'], 'FK_Medicines_MedicineForms_MedicineFormId')->references(['Id'])->on('MedicineForms')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Medicines', function (Blueprint $table) {
            $table->dropForeign('FK_Medicines_MedicineForms_MedicineFormId');
        });
    }
};
