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
        Schema::table('PatientHistoryPanchakarmas', function (Blueprint $table) {
            $table->foreign(['PanchakarmaId'], 'FK_PatientHistoryPanchakarmas_Panchakarmas_PanchakarmaId')->references(['Id'])->on('Panchakarmas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['PatientHistoryId'], 'FK_PatientHistoryPanchakarmas_PatientHistories_PatientHistoryId')->references(['Id'])->on('PatientHistories')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('PatientHistoryPanchakarmas', function (Blueprint $table) {
            $table->dropForeign('FK_PatientHistoryPanchakarmas_Panchakarmas_PanchakarmaId');
            $table->dropForeign('FK_PatientHistoryPanchakarmas_PatientHistories_PatientHistoryId');
        });
    }
};
