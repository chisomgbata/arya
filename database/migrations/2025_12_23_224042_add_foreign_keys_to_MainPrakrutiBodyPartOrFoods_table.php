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
        Schema::table('MainPrakrutiBodyPartOrFoods', function (Blueprint $table) {
            $table->foreign(['BodyPartOrFoodId'], 'FK_MainPrakrutiBodyPartOrFoods_BodyPartOrFoods_BodyPartOrFoodId')->references(['Id'])->on('BodyPartOrFoods')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['MainPrakrutiId'], 'FK_MainPrakrutiBodyPartOrFoods_MainPrakrutis_MainPrakrutiId')->references(['Id'])->on('MainPrakrutis')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('MainPrakrutiBodyPartOrFoods', function (Blueprint $table) {
            $table->dropForeign('FK_MainPrakrutiBodyPartOrFoods_BodyPartOrFoods_BodyPartOrFoodId');
            $table->dropForeign('FK_MainPrakrutiBodyPartOrFoods_MainPrakrutis_MainPrakrutiId');
        });
    }
};
