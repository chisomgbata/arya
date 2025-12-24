<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('DiseaseSymptoms', function (Blueprint $table) {
            $table->integer('DiseaseId');
            $table->integer('SymptomId');
            $table->boolean('IsMain')->default(false);

            $table->primary(['DiseaseId', 'SymptomId'], 'pk_diseasesymptoms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DiseaseSymptoms');
    }
};
