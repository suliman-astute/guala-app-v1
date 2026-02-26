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
            $table->string('machine_no')->nullable()->index(); // Added for view join
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
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('table_guaprodrouting', function (Blueprint $table) {
            $table->id();
            $table->string('mesOrderNo')->index();
            $table->timestamps();
        });

        Schema::create('ordini_lavoro_lotti', function (Blueprint $table) {
            $table->id();
            $table->string('lotto')->index();
            $table->timestamps();
        });
    }
};
