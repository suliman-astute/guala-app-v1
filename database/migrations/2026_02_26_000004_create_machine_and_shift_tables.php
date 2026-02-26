<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('machine_center', function (Blueprint $table) {
            $table->id();
            $table->string('no')->index();
            $table->string('GUAPosition')->index();
            $table->timestamps();
        });

        Schema::create('tabella_appoggio_macchine', function (Blueprint $table) {
            $table->id();
            $table->string('id_mes')->index();
            $table->string('id_piovan')->index();
            $table->timestamps();
        });

        Schema::create('turni', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->time('inizio');
            $table->time('fine');
            $table->timestamps();
        });

        Schema::create('gestione_turni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_capoturno')->index();
            $table->unsignedBigInteger('id_turno')->index();
            $table->date('data_turno')->index();
            $table->timestamps();
        });

        Schema::create('gestione_turni_presse', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('note_macchine_operatori', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_macchina')->index();
            $table->unsignedBigInteger('id_operatore')->index();
            $table->date('data');
            $table->unique(['id_macchina', 'id_operatore', 'data'], 'uq_macc_op_data');
            $table->timestamps();
        });

        Schema::create('ordine_note', function (Blueprint $table) {
            $table->id();
            $table->string('ordine')->index();
            $table->string('lotto')->index();
            $table->unique(['ordine', 'lotto']);
            $table->timestamps();
        });
    }
};
