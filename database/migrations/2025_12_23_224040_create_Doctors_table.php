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
        Schema::create('Doctors', function (Blueprint $table) {
            $table->increments('Id');
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->uuid('CreatedBy');
            $table->dateTime('ModifiedDate', 7)->nullable()->useCurrent();
            $table->uuid('ModifiedBy');
            $table->dateTime('DeletedDate', 7)->nullable();
            $table->uuid('DeletedBy');
            $table->boolean('IsDeleted');
            $table->text('ClinicName')->nullable();
            $table->text('ClinicUrl')->nullable();
            $table->text('FirstName')->nullable();
            $table->text('LastName')->nullable();
            $table->text('Email')->nullable();
            $table->text('MobileNo')->nullable();
            $table->text('Address')->nullable();
            $table->integer('CityId')->nullable();
            $table->integer('StateId')->nullable();
            $table->text('Gender')->nullable();
            $table->text('PrescriptionUrl')->nullable();

            $table->primary(['Id'], 'pk_doctors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Doctors');
    }
};
