<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('site_id')->constrained('sites');
            $table->string('user_id')->nullable();
            $table->string('matricola')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('active_apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('active_app_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('active_app_id')->constrained('active_apps')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('table_gestione_ad', function (Blueprint $table) {
            $table->id();
            $table->string('host');
            $table->string('base_dn');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('table_gestione_ad');
        Schema::dropIfExists('active_app_user');
        Schema::dropIfExists('active_apps');
        Schema::dropIfExists('users');
    }
};
