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
        Schema::create('Diseases', function (Blueprint $table) {
            $table->increments('Id');
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->uuid('CreatedBy');
            $table->dateTime('ModifiedDate', 7)->nullable()->useCurrent();
            $table->uuid('ModifiedBy');
            $table->dateTime('DeletedDate', 7)->nullable();
            $table->uuid('DeletedBy');
            $table->boolean('IsDeleted');
            $table->string('Name', 450)->nullable();
            $table->text('Introduction')->nullable();
            $table->text('Purvaroopa')->nullable();
            $table->text('DoDont')->nullable();
            $table->text('Sadhyabadyatva')->nullable();
            $table->text('ChikitsaSutra')->nullable();
            $table->text('Samprapti')->nullable();
            $table->text('Upadrava')->nullable();
            $table->text('Panchakarma')->nullable();
            $table->text('Causes')->nullable();
            $table->text('ArishtaLaxana')->nullable();
            $table->text('DifferentialDiagnosis')->nullable();
            $table->text('LaboratoryInvestions')->nullable();

            $table->primary(['Id'], 'pk_diseases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Diseases');
    }
};
