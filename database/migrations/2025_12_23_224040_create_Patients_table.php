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
        Schema::create('Patients', function (Blueprint $table) {
            $table->uuid('Id');
            $table->string('FirstName', 450)->nullable();
            $table->string('LastName', 450)->nullable();
            $table->dateTime('BirthDate', 7)->nullable();
            $table->float('AgeYear')->nullable();
            $table->float('AgeMonth')->nullable();
            $table->float('Weight')->nullable();
            $table->string('MobileNo', 450)->nullable();
            $table->string('Address', 450)->nullable();
            $table->string('Email', 450)->nullable();
            $table->uuid('DoctorUserId');
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->uuid('CreatedBy');
            $table->dateTime('ModifiedDate', 7)->nullable()->useCurrent();
            $table->uuid('ModifiedBy');
            $table->dateTime('DeletedDate', 7)->nullable();
            $table->uuid('DeletedBy');
            $table->boolean('IsDeleted');
            $table->string('MiddleName', 450)->nullable();
            $table->text('OtherIdNumber')->nullable();
            $table->string('AgeGroup', 450)->nullable();
            $table->string('Gender', 450)->nullable();
            $table->boolean('IsNew')->default(false);

            $table->primary(['Id'], 'pk_patients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Patients');
    }
};
