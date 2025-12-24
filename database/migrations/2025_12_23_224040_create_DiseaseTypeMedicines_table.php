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
        Schema::create('DiseaseTypeMedicines', function (Blueprint $table) {
            $table->increments('Id');
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->uuid('CreatedBy');
            $table->dateTime('ModifiedDate', 7)->nullable()->useCurrent();
            $table->uuid('ModifiedBy');
            $table->dateTime('DeletedDate', 7)->nullable();
            $table->uuid('DeletedBy');
            $table->boolean('IsDeleted');
            $table->integer('DiseaseTypeId');
            $table->integer('MedicineId');
            $table->text('Dose')->nullable();
            $table->integer('TimeOfAdministrationId');
            $table->integer('AnupanaId');
            $table->text('Duration')->nullable();
            $table->boolean('IsSpecial')->default(false);
            $table->integer('OrderNumber')->nullable();
            $table->boolean('IsLevel3')->default(false);
            $table->boolean('IsLevel1')->default(false);
            $table->boolean('IsLevel2')->default(false);

            $table->primary(['Id'], 'pk_diseasetypemedicines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DiseaseTypeMedicines');
    }
};
