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
        Schema::create('PatientHistories', function (Blueprint $table) {
            $table->uuid('Id');
            $table->uuid('PatientId');
            $table->uuid('DoctorId');
            $table->text('Remark')->nullable();
            $table->text('Note')->nullable();
            $table->float('ConsultationFee');
            $table->float('MedicinesFee');
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->uuid('CreatedBy');
            $table->dateTime('ModifiedDate', 7)->nullable()->useCurrent();
            $table->uuid('ModifiedBy');
            $table->dateTime('DeletedDate', 7)->nullable();
            $table->uuid('DeletedBy');
            $table->boolean('IsDeleted');
            $table->dateTime('NextAppointmentDate', 7)->nullable();
            $table->boolean('IsHetuPariksa')->default(false);
            $table->boolean('IsLaboratoryReport')->default(false);
            $table->boolean('IsPanchakarma')->default(false);
            $table->boolean('IsRogaPariksa')->default(false);
            $table->boolean('IsVital')->default(false);
            $table->boolean('IsWomenHistory')->default(false);
            $table->boolean('IsImages')->default(false);

            $table->primary(['Id'], 'pk_patienthistories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PatientHistories');
    }
};
