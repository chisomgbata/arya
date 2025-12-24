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
        Schema::create('Medicines', function (Blueprint $table) {
            $table->increments('Id');
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->uuid('CreatedBy');
            $table->dateTime('ModifiedDate', 7)->nullable()->useCurrent();
            $table->uuid('ModifiedBy');
            $table->dateTime('DeletedDate', 7)->nullable();
            $table->uuid('DeletedBy');
            $table->boolean('IsDeleted');
            $table->string('Name', 450)->nullable();
            $table->text('CompanyName')->nullable();
            $table->integer('MedicineFormId');
            $table->text('Description')->nullable();
            $table->boolean('IsPattern')->default(false);
            $table->boolean('IsSpecial')->default(false);

            $table->primary(['Id'], 'pk_medicines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Medicines');
    }
};
