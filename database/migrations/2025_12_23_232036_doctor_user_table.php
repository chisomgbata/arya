<?php

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('DoctorUsers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'UserId')->nullable()->index();
            $table->foreignIdFor(Doctor::class, 'DoctorId')->nullable()->index();
            $table->string('role');

            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(User::class )->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('DoctorUsers');
        Schema::dropIfExists('sessions');
    }
};
