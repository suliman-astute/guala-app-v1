<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->unsignedBigInteger('site_id')->index();
            $table->string('user_id')->index(); // Legacy Key
            $table->string('matricola')->index(); // Employee Key
            $table->string('name')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('active_apps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->index();
            $table->string('code')->unique(); // Module Key
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('active_app_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('active_app_id')->index();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('active_app_user');
        Schema::dropIfExists('active_apps');
        Schema::dropIfExists('users');
    }
};
