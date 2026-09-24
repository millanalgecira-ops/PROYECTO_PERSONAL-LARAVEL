<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Vistas de solo lectura (paridad con el proyecto original).
     * La aplicacion no depende de ellas para funcionar: son utilitarias
     * para consultas directas en la base de datos (phpMyAdmin, reportes...).
     */
    public function up(): void
    {
        DB::statement('
            CREATE VIEW vw_catalogo_productos AS
            SELECT
              c.id AS categoria_id, c.nombre AS categoria,
              p.id AS producto_id, p.nombre AS producto,
              p.descripcion, p.precio, p.popular, p.disponible
            FROM categorias c
            LEFT JOIN productos p ON p.categoria_id = c.id
            WHERE c.activa = 1
        ');

        DB::statement('
            CREATE VIEW vw_resumen_ventas_diarias AS
            SELECT
              DATE(p.creado_en) AS fecha,
              COUNT(DISTINCT p.id) AS pedidos,
              COALESCE(SUM(pg.total_pagado), 0) AS total_vendido
            FROM pedidos p
            LEFT JOIN pagos pg ON pg.pedido_id = p.id
            WHERE p.estado IN (\'Pagado\', \'Entregado\')
            GROUP BY DATE(p.creado_en)
        ');
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_resumen_ventas_diarias');
        DB::statement('DROP VIEW IF EXISTS vw_catalogo_productos');
    }
};
