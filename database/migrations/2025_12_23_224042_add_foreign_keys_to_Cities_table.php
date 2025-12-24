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
        Schema::table('Cities', function (Blueprint $table) {
            $table->foreign(['StateId'], 'FK_Cities_States_StateId')->references(['Id'])->on('States')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Cities', function (Blueprint $table) {
            $table->dropForeign('FK_Cities_States_StateId');
        });
    }
};
