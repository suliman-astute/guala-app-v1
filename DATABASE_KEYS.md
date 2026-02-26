# Database Keys Reference

This document provides a reference for the various keys used across the Guala App to maintain logical consistency and integrate with external systems.

## 1. Primary & Foreign Keys (MySQL Core)

| Key | Type | Tables Connected | Meaning/Usage |
| :--- | :--- | :--- | :--- |
| `id` | Primary | All Tables | Unique identifier for each record. |
| `user_id` | Foreign | `active_app_user` → `users` | Links users to their application-specific permissions. |
| `site_id` | Foreign | `users`, `active_apps` → `sites` | Associates users and modules with a physical location. |
| `id_turno` | Foreign | `gestione_turni` → `turni` | Links daily shift management to a shift definition. |
| `id_macchina` | Foreign | `note_macchine_operatori` → `machine_center` | Links operator notes to a specific machine. |

## 2. Integration & Correlation Keys (System-wide)

These keys allow the application to join data originating from different sources (MES, ERP, WMS).

| Key | Sources | Related Tables | Meaning/Usage |
| :--- | :--- | :--- | :--- |
| `mesOrderNo` | BC API, sqlsrv1 | `table_gua_mes_prod_orders`, `table_gua_items_in_producion`, `orderfrommes`, `stampaggio_view` | **Unique Production Order ID** from the MES. Used to track a job's status and production quantity across all systems. |
| `itemNo` | BC API, sqlsrv2 | `table_gua_mes_prod_orders`, `bom_explosion`, `codici_oggetti` | **Item/SKU Code**. Used to pull Bill of Material (BOM) data and determine the product family. |
| `GUAPosition` | sqlsrv2 | `machine_center`, `presse_guala_fp`, `assemblaggio_view` | **Physical Slot/Position** of a machine on the factory floor. Used as a unique identifier for machines across different tracking systems. |
| `no` / `id_mes` | sqlsrv2, BC API | `machine_center`, `tabella_appoggio_macchine`, `presse_guala_fp` | **MES Machine Code**. Links physical machines to their tracking identifiers in the MES and BC systems. |
| `id_piovan` | SOAP API | `tabella_appoggio_macchine`, `table_piovan_import` | **Piovan Device ID**. Used to query the Piovan SOAP API for real-time material and lot information. |
| `lotto` | INCAS, Piovan | `ordini_lavoro_lotti`, `ordine_note`, `table_piovan_import` | **Production Lot Number**. Links work orders to material batches used during production. |

## 3. Legacy & Employee Keys

| Key | Table | Usage |
| :--- | :--- | :--- |
| `user_id` | `users` | Legacy Key from previous systems. |
| `matricola` | `users` | Employee Key for HR/Payroll integration. |
