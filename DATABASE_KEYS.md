# Database Keys Reference (Verified Alignment)

This document provides a complete and verified reference for all keys used in the Guala App to maintain logical consistency across its internal modules and external systems.

## 1. Core Structural Keys (MySQL)

| Key | Type | Connected Tables | Purpose |
| :--- | :--- | :--- | :--- |
| `id` | Primary | All Tables | Unique record identifier. |
| `site_id` | Foreign / Index | `users`, `active_apps` | Associates data with physical locations (Italy, Romania). |
| `user_id` | Foreign / Index | `active_app_user`, `users` | Links users to permissions. |
| `id_turno` | Foreign / Index | `gestione_turni` | Links daily shifts to shift definitions. |
| `id_macchina` | Foreign / Index | `note_macchine_operatori` | Links operator notes to specific machines. |

## 2. Integration & Correlation Keys (System-wide)

These keys are the backbone of the system's "alignment" with external MES, ERP, and WMS sources.

| Key | Related Systems | Core Tables | Usage |
| :--- | :--- | :--- | :--- |
| `mesOrderNo` | BC API, sqlsrv1, INCAS | `table_gua_mes_prod_orders`, `orderfrommes`, `ordini_lavoro_lotti`, `stampaggio_view` | **Unique Production Order ID**. Tracks job status and quantities across all systems. |
| `itemNo` | BC API, sqlsrv2 | `table_gua_mes_prod_orders`, `bom_explosion`, `codici_oggetti` | **Item/SKU Code**. Pulls BOM data and determines product family. |
| `GUAPosition` | sqlsrv2 | `machine_center`, `presse_guala_fp`, `assemblaggio_view` | **Physical Slot/Position**. Unique identifier for machines on the factory floor. |
| `no` / `id_mes` | sqlsrv2, BC API | `machine_center`, `tabella_appoggio_macchine`, `presse_guala_fp` | **MES Machine Code**. Links machines to tracking identifiers in MES and BC. |
| `id_piovan` | SOAP API | `tabella_appoggio_macchine`, `table_piovan_import` | **Piovan Device ID**. Used to query the Piovan SOAP API for material/lot info. |
| `lotto` | INCAS, Piovan | `ordini_lavoro_lotti`, `ordine_note`, `table_piovan_import` | **Production Lot Number**. Links orders to material batches. |

## 3. Specialized Keys

| Key | Table | Usage |
| :--- | :--- | :--- |
| `user_id` | `users` | Legacy Key for system migration. |
| `matricola` | `users` | Employee Key for HR synchronization. |
| `code` | `active_apps` | Unique Module Key for application logic. |
| `stampe` / `seq` | `ext_infos` | Keys for legacy printing and sequence tracking. |
