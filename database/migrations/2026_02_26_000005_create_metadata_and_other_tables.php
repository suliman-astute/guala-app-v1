<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dictionary_table', function (Blueprint $table) {
            $table->id();
            $table->string('tabella');
            $table->string('colonna');
            $table->string('it');
            $table->string('en');
            $table->timestamps();
        });

        Schema::create('codici_oggetti', function (Blueprint $table) {
            $table->id();
            $table->string('itemNo')->index();
            $table->timestamps();
        });

        Schema::create('enpoint_piovan', function (Blueprint $table) {
            $table->id();
            $table->string('url');
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

        Schema::create('commento_lavori_guala_fp', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('qta_guala_pro_rom', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('bisio_progetti_stain', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('bisio_progetti_stain');
        Schema::dropIfExists('qta_guala_pro_rom');
        Schema::dropIfExists('commento_lavori_guala_fp');
        Schema::dropIfExists('ext_infos');
        Schema::dropIfExists('table_piovan_import');
        Schema::dropIfExists('enpoint_piovan');
        Schema::dropIfExists('codici_oggetti');
        Schema::dropIfExists('dictionary_table');
    }
};
