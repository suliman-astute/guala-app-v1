# Report on PDF Functionality in Legacy Version

This report details the implementation of the "PDF/Print" functionality tracked from the following routes:

1.  `app1.pdf_stampaggio.stampa` (`/APP1/PDF_Stampaggio/stampa`)
2.  `app1.pdf.stampa` (`/APP1/PDF/stampa`)
3.  `monitor_fp.pdf_stampaggio_fp.stampa` (`/Monitor_Fp/PDF_Stampaggio_Fp/stampa`)

## 1. Overview

Contrary to generating a PDF file on the server (e.g., using libraries like DOMPDF or Snappy), the "stampa" (print) functionality in these controllers **renders a standard HTML Blade view**. This view is styled specifically for printing (using `@media print` CSS) and typically includes a JavaScript trigger (`window.print()`) or expects the user to use the browser's print function to "Save as PDF".

## 2. Detailed Analysis by Route

### A. Stampaggio (Molding) Report
*   **Route:** `/APP1/PDF_Stampaggio/stampa`
*   **Controller:** `App\Http\Controllers\stampaggiotableViewController`
*   **Method:** `stampa`
*   **View:** `app.APP1.PDF_Stampaggio.index`

**Logic:**
1.  **Data Retrieval**: Queries `DB::table('stampaggio_view')`.
    *   Filters out invalid `mesOrderNo` and `itemNo`.
    *   Orders by `machinePressFull`, `GUAPosition`, `relSequence`.
2.  **Grouping**:
    *   Groups rows by `machinePressFull`.
    *   Checks for the existence of a pre-generated PDF file in `public_path("bolle_lavorazione_pdf/{$row->mesOrderNo}.pdf")`. This is a separate file (likely a work order) linked to the row, not the report being generated.
3.  **Data Preparation**:
    *   Flattens the grouped data into a single array `$result`.
    *   Calculates `quantita_rimanente` (Remaining Quantity).
4.  **View Rendering**:
    *   The view iterates through `$righe`.
    *   It detects changes in `machinePressFull` to insert a "Group Header" row (`<tr class="tr-machine">`).
    *   Displays columns: Sequence, Code, Description, MES Order, Order Qty, Produced Qty, Remaining Qty, Status (Prod/Stop/Ok).

### B. Assemblaggio (Assembly) Report
*   **Route:** `/APP1/PDF/stampa`
*   **Controller:** `App\Http\Controllers\assemblaggiotableViewController`
*   **Method:** `stampa`
*   **View:** `app.APP1.PDF.index`

**Logic:**
1.  **Data Retrieval**: Queries `DB::table('assemblaggio_view')`.
    *   Filters invalid orders.
    *   Orders by `family`, `nome_completo_macchina`, `relSequence`.
2.  **Grouping**:
    *   Groups by `family`, then by `nome_completo_macchina`.
    *   Explicitly excludes machine "MPACK - PACKAGING".
3.  **Data Preparation**:
    *   Flattens the structure, inserting "Group Header" items directly into the array with flags:
        *   `'is_group' => true, 'group_type' => 'family'`
        *   `'is_group' => true, 'group_type' => 'machine'`
4.  **View Rendering**:
    *   Uses `@if` blocks to check `is_group` and `group_type` to render appropriate header rows (`tr-family`, `tr-machine`).
    *   Displays similar columns to Stampaggio.

### C. Monitor FP (Food/Pharma) Report
*   **Route:** `/Monitor_Fp/PDF_Stampaggio_Fp/stampa`
*   **Controller:** `App\Http\Controllers\ProductionFPController`
*   **Method:** `stampa`
*   **View:** `app.Monitor_Fp.PDF_Stampaggio_Fp.index`

**Logic:**
1.  **Data Retrieval**: Queries `App\Models\ProductionFP`.
    *   Applies date filters (`from`, `to`).
    *   Enriches data with:
        *   **Machines**: Joins/lookups on `Macchine` model for `GUAPosition` and names.
        *   **Quantities**: Lookups `table_guaprodrouting` for `TotaleQtaProdottaBuoni`.
        *   **Status**: Lookups `table_guaprodrouting` for `StatoOperazione`.
        *   **Comments**: Lookups `table_commenti_guala_fp` and `commento_lavori_guala_fp`.
2.  **Grouping**:
    *   Can return a single dataset or split into `pharma` and `food` based on `GUA_schedule`.
    *   Uses a `groupByMachine` helper to structure data with header rows (`is_group => true`).
3.  **View Rendering**:
    *   Handles merged arrays (`$righePH` + `$righeF`) if needed.
    *   Renders machine headers.
    *   Displays columns including **Comments** and calculated status indicators (dots).

## 3. View Structure & Styling

All three views share a common structure:
*   **HTML5** document with inline CSS in `<head>`.
*   **CSS Variables**: Used for colors (`--group1`, `--group2` for row banding).
*   **Print Styles (`@media print`)**:
    *   Hides action buttons (`.actions { display:none }`).
    *   Sets page size to A4 portrait.
    *   Adjusts font sizes (typically 11px) for printing.
    *   Forces color printing (`print-color-adjust: exact`).
*   **Table Structure**: Standard `<table>` with `<thead>` and `<tbody>`.
*   **Dynamic Rows**: PHP loops (`@foreach`) render data rows and inject header rows for groups (Family/Machine) based on change detection or specific group items in the array.

## 4. Key Findings

1.  **No Server-Side PDF Generation**: The system relies on the client (browser) to generate the PDF via the Print dialog.
2.  **"Bolle Lavorazione"**: The code references existing PDFs (`bolle_lavorazione_pdf/{order}.pdf`), checking if they exist on the filesystem (`public_path`). These are likely uploaded or generated by a different process (MES/ERP integration) and are merely linked here, not created.
3.  **Data Enrichment**: The FP controller (`ProductionFPController`) is significantly more complex, pulling data from multiple sources (ProductionFP, Macchine, routing tables, comment tables) compared to the view-based retrieval of the other two.