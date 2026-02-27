<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('aziende', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->nullable();
            $table->timestamps();
        });

        Schema::create('table_gestione_ad', function (Blueprint $table) {
            $table->id();
            $table->string('host')->nullable();
            $table->string('base_dn')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('table_gestione_ad');
        Schema::dropIfExists('aziende');
        Schema::dropIfExists('sites');
    }
};
