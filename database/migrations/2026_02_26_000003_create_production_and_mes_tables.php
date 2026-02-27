<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('table_gua_mes_prod_orders', function (Blueprint $table) {
            $table->id();
            $table->string('mesOrderNo')->index();
            $table->string('itemNo')->index();
            $table->string('machine_no')->nullable();
            $table->timestamps();
        });

        Schema::create('gua_mes_prod_orders_fp', function (Blueprint $table) {
            $table->id();
            $table->string('mesOrderNo')->index();
            $table->timestamps();
        });

        Schema::create('table_gua_items_in_producion', function (Blueprint $table) {
            $table->id();
            $table->string('mesOrderNo')->index();
            $table->string('itemNo')->index();
            $table->timestamps();
        });

        Schema::create('gua_items_in_producion_fp', function (Blueprint $table) {
            $table->id();
            $table->string('mesOrderNo')->index();
            $table->timestamps();
        });

        Schema::create('bom_explosion', function (Blueprint $table) {
            $table->id();
            $table->string('itemNo')->index();
            $table->timestamps();
        });

        Schema::create('orderfrommes', function (Blueprint $table) {
            $table->id();
            $table->string('mesOrderNo')->index();
            $table->timestamps();
        });

        Schema::create('table_guaprodrouting', function (Blueprint $table) {
            $table->id();
            $table->string('mesOrderNo')->index();
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

        Schema::create('ordini_lavoro_lotti', function (Blueprint $table) {
            $table->id();
            $table->string('mesOrderNo')->index();
            $table->string('lotto')->index();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ordini_lavoro_lotti');
        Schema::dropIfExists('bisio_progetti_stain');
        Schema::dropIfExists('qta_guala_pro_rom');
        Schema::dropIfExists('commento_lavori_guala_fp');
        Schema::dropIfExists('table_guaprodrouting');
        Schema::dropIfExists('orderfrommes');
        Schema::dropIfExists('bom_explosion');
        Schema::dropIfExists('gua_items_in_producion_fp');
        Schema::dropIfExists('table_gua_items_in_producion');
        Schema::dropIfExists('gua_mes_prod_orders_fp');
        Schema::dropIfExists('table_gua_mes_prod_orders');
    }
};
