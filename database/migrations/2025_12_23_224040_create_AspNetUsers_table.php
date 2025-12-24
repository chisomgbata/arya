<?php

use App\Models\User;
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
        Schema::create('AspNetUsers', function (Blueprint $table) {
            $table->uuid('Id');
            $table->string('Email')->unique();
            $table->timestamp('EmailVerifiedAt')->nullable();
            $table->text('Password')->nullable();
            $table->text('PhoneNumber')->nullable();
            $table->text('FirstName')->nullable();
            $table->text('LastName')->nullable();
            $table->boolean('IsAdmin');
            $table->rememberToken();
            $table->dateTime('CreatedDate', 7)->nullable();
            $table->dateTime('ModifiedDate', 7)->nullable()->useCurrent();
            $table->primary(['Id'], 'pk_aspnetusers');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AspNetUsers');
    }
};
