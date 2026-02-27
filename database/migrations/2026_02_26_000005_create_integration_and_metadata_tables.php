<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dictionary_table', function (Blueprint $table) {
            $table->id();
            $table->string('tabella')->nullable();
            $table->string('colonna')->nullable();
            $table->string('it')->nullable();
            $table->string('en')->nullable();
            $table->timestamps();
        });

        Schema::create('codici_oggetti', function (Blueprint $table) {
            $table->id();
            $table->string('itemNo')->index();
            $table->timestamps();
        });

        Schema::create('enpoint_piovan', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        Schema::create('table_piovan_import', function (Blueprint $table) {
            $table->id();
            $table->string('id_piovan')->index();
            $table->string('lotto')->index();
            $table->timestamps();
        });

        Schema::create('ext_infos', function (Blueprint $table) {
            $table->id();
            $table->string('stampe')->index();
            $table->integer('seq')->index();
            $table->string('code')->index();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ext_infos');
        Schema::dropIfExists('table_piovan_import');
        Schema::dropIfExists('enpoint_piovan');
        Schema::dropIfExists('codici_oggetti');
        Schema::dropIfExists('dictionary_table');
    }
};
