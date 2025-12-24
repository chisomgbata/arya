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
        Schema::create('PatientHistoryMedicines', function (Blueprint $table) {
            $table->uuid('Id');
            $table->uuid('PatientHistoryId');
            $table->integer('MedicineId');
            $table->text('Dose')->nullable();
            $table->string('TimeOfAdministration', 450)->nullable();
            $table->string('Duration', 450)->nullable();
            $table->text('Anupana')->nullable();
            $table->text('MedicineFormName')->nullable();
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->uuid('CreatedBy');
            $table->dateTime('ModifiedDate', 7)->nullable()->useCurrent();
            $table->uuid('ModifiedBy');
            $table->dateTime('DeletedDate', 7)->nullable();
            $table->uuid('DeletedBy');
            $table->boolean('IsDeleted');
            $table->text('Amount')->nullable();

            $table->primary(['Id'], 'pk_patienthistorymedicines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PatientHistoryMedicines');
    }
};
