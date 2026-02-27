# API Data Sourcing Analysis: `GET /json`

**Controller:** `App\Http\Controllers\OrdiniController`
**Method:** `json`
**Route:** `/json` (name: `json`)

## Overview
This endpoint retrieves daily shift data (`turni`) for pressing machines. It supports filtering by date and automatically restricts non-admin users to shifts where they are listed as operators. The response includes enriched machine information and associated SAP orders.

## Data Sources

The data is sourced from the following database tables:

### 1. `gestione_turni_presse` (Main Source)
- **Role:** Primary table for shift data.
- **Alias:** `gtp`
- **Key Columns Used:**
    - `data_turno`: Filtered by the requested date (`?data=YYYY-MM-DD`) or today's date.
    - `id_operatori`: JSON column containing operator IDs. Used for access control (non-admins see only their shifts).
    - `id_macchinari_associati`: JSON column containing IDs of machines associated with the shift.

### 2. `machine_center`
- **Role:** Master table for machine information.
- **Alias:** `mc`
- **Key Columns Used:**
    - `id`: Primary Key. Used to match IDs from `gestione_turni_presse.id_macchinari_associati`.
    - `no`: Join key for auxiliary table.
    - `name`: Machine name.
    - `GUAMachineCenterType`: Filtered to include only 'Pressing' machines.

### 3. `tabella_appoggio_macchine`
- **Role:** Auxiliary table mapping machine internal IDs to MES and Piovan system IDs.
- **Alias:** `tam`
- **Join Condition:** `tam.no` = `mc.no` (Left Join)
- **Key Columns Used:**
    - `id_mes`: MES Identifier (used for display and further joins).
    - `id_piovan`: Piovan Identifier.

### 4. `bisio_progetti_stain`
- **Role:** Source for SAP Order numbers linked to the MES system.
- **Alias:** `bps`
- **Join Condition:** `bps.nome` = `tam.id_mes` (Left Join)
- **Key Columns Used:**
    - `nrordinesap`: The SAP Order Number.

## Logic Flow

1.  **Authentication & Authorization**:
    -   The user is identified via `Auth::user()`.
    -   If the user is **not** an admin (`admin != 1`), a filter is applied to `gestione_turni_presse` to only include rows where `id_operatori` contains the user's ID.

2.  **Date Filtering**:
    -   Defaults to today's date (`Carbon::today()`).
    -   Can be overridden via the `data` query parameter.

3.  **Data Extraction (Phase 1 - Shifts)**:
    -   Queries `gestione_turni_presse` based on date and user permissions.
    -   Retrieves raw shift rows.

4.  **Machine ID Extraction**:
    -   Iterates through the retrieved shifts.
    -   Decodes the `id_macchinari_associati` JSON field to collect a unique list of all machine IDs involved in the fetched shifts.

5.  **Data Extraction (Phase 2 - Enrichment)**:
    -   Queries `machine_center` for the collected IDs.
    -   Joins with `tabella_appoggio_macchine` to get `id_mes` and `id_piovan`.
    -   Joins with `bisio_progetti_stain` to get `nrordinesap`.
    -   **Note:** This step ensures that we only fetch details for machines that are actually present in the filtered shifts.

6.  **Response Construction**:
    -   The code groups the enriched machine data by machine ID.
    -   It iterates over the original shift rows and injects a `macchinari` array into each row.
    -   The `macchinari` array contains objects with:
        -   `id`: Machine ID
        -   `label`: Formatted string (`id_mes - id_piovan (name)`)
        -   `ordini`: List of unique SAP orders (`nrordinesap`) associated with that machine.
    -   Returns a JSON response with the structure `{'data': [...]}`.