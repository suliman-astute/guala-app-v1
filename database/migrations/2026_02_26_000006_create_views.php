<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::statement("
            CREATE VIEW stampaggio_view AS
            SELECT o.*, m.GUAPosition
            FROM table_gua_mes_prod_orders o
            JOIN machine_center m ON o.machine_no = m.no
            WHERE o.itemNo LIKE '%ST%'
        ");

        DB::statement("
            CREATE VIEW assemblaggio_view AS
            SELECT o.*, m.no as machine_name
            FROM table_gua_mes_prod_orders o
            JOIN machine_center m ON o.machine_no = m.no
            WHERE o.itemNo LIKE '%AS%'
        ");
    }

    public function down(): void {
        DB::statement("DROP VIEW IF EXISTS stampaggio_view");
        DB::statement("DROP VIEW IF EXISTS assemblaggio_view");
    }
};
