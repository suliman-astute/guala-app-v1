# Quantity Tracking Report (`quantita_prodotta`)

This report details how the `quantita_prodotta` (Produced Quantity) value is calculated, stored, and displayed in the Guala application.

## 1. Overview

The `quantita_prodotta` value is **not** created within the Guala application itself. Instead, it is synchronized from external systems (STAIN / MDW) via a scheduled script (`scripts/db_aligner.php`) and stored in local MySQL tables. The frontend APIs then consume these tables via SQL Views or direct queries.

## 2. Data Flow Summary

| Module | API Controller | Data Source (MySQL) | External Source (Sync Origin) |
| :--- | :--- | :--- | :--- |
| **Molding (Stampaggio)** | `stampaggiotableViewController` | `stampaggio_view` -> `table_gua_mes_prod_orders` | MDW (`GoodQuantityMonitoring`) |
| **Assembly (Assemblaggio)** | `assemblaggiotableViewController` | `assemblaggio_view` -> `table_gua_mes_prod_orders` | MDW (`bm20.IndicatorValueEmulation`) |
| **Monitor FP** | `ProductionFPController` | `table_guaprodrouting` | STAIN (`tb_odp_QuantitaProdotteOperazioni`) |

---

## 3. Detailed Analysis by Module

### A. Molding (Stampaggio) & Assembly (Assemblaggio)

**1. API Layer**
*   **Controllers**: `stampaggiotableViewController` and `assemblaggiotableViewController`.
*   **Method**: `index()`.
*   **Logic**:
    *   Queries `stampaggio_view` or `assemblaggio_view`.
    *   Calculates `quantita_rimanente` (Remaining Quantity) as:
        ```php
        $item->quantita_rimanente = $item->quantity - $item->quantita_prodotta;
        ```
    *   Returns the data as JSON.

**2. Database Layer**
*   **Views**: `stampaggio_view` and `assemblaggio_view` are SQL Views that select from the table `table_gua_mes_prod_orders`.
*   **Column**: `quantita_prodotta`.

**3. Synchronization Logic (`scripts/db_aligner.php`)**
The `quantita_prodotta` field in `table_gua_mes_prod_orders` is updated as follows:

*   **For Assembly Orders (Orders containing "AS")**:
    *   **Source**: SQL Server (MDW - `192.168.50.65`).
    *   **Query**: Sums the `value` from `bm20.IndicatorValueEmulation` where the indicator key starts with "Good".
    *   **SQL**:
        ```sql
        SELECT ROUND(SUM(value),0) AS totale
        FROM bm20.IndicatorValueEmulation t1
        JOIN bm20.OperationExecution t3 ON t1.OperationExecutionId=t3.OperationExecutionId
        JOIN bm20.Indicator ind ON ind.IndicatorId=t1.Indicatorid
        WHERE ind.IndicatorKey LIKE 'Good%'
          AND t3.OperationExecutionkey = '{mesOrderNo}'
        ```

*   **For Molding Orders (Others)**:
    *   **Source**: SQL Server (MDW - `192.168.50.65`).
    *   **Query**: Sums the `good` column from `GoodQuantityMonitoring`.
    *   **SQL**:
        ```sql
        SELECT SUM(good) AS totale
        FROM GoodQuantityMonitoring
        WHERE ordername='{mesOrderNo}'
        ```

---

### B. Monitor FP

**1. API Layer**
*   **Controller**: `ProductionFPController`.
*   **Method**: `index()`.
*   **Logic**:
    *   Queries the `ProductionFP` model (which maps to `table_gua_mes_prod_orders` or similar).
    *   **Dynamic Enrichment**: It does **not** use the `quantita_prodotta` from the main order table.
    *   Instead, it fetches the quantity from `table_guaprodrouting` by matching `prodOrderNo` and `operationNo`.
    *   Code Reference:
        ```php
        $qtaRows = DB::table('table_guaprodrouting')
            ->select('prodOrderNo','operationNo','TotaleQtaProdottaBuoni')
            ...
        $totBuoni = $qtaByKey[$routingKey] ?? 0;
        'quantita_prodotta' => (int) $totBuoni,
        ```

**2. Database Layer**
*   **Table**: `table_guaprodrouting`.
*   **Column**: `TotaleQtaProdottaBuoni`.

**3. Synchronization Logic (`scripts/db_aligner.php`)**
The `TotaleQtaProdottaBuoni` field in `table_guaprodrouting` is updated as follows:

*   **Source**: SQL Server (STAIN - `192.168.30.1`).
*   **Query**: Selects `TotaleQtaProdottaBuoni` from `tb_odp_QuantitaProdotteOperazioni`.
*   **SQL**:
    ```sql
    SELECT Q.TotaleQtaProdottaBuoni
    FROM [BisioProgetti_STAINplus].[dbo].[tb_odp_QuantitaProdotteOperazioni] AS Q
    INNER JOIN [BisioProgetti_STAINplus].[dbo].[tb_odp_OrdiniOperazioni] AS OO 
        ON Q.IDOperazioneOdp = OO.IDOperazioneOdp
    INNER JOIN [BisioProgetti_STAINplus].[dbo].[tb_odp_OrdiniTestata] AS T 
        ON OO.IDOrdine = T.IDOrdine
    WHERE T.NrOrdineSAP = '{prodOrderNo}'
      AND OO.OperazioneCiclo = '{operationNo}'
    ```

## 4. Conclusion

*   **Inconsistency Risk**: There are two different logic paths for calculating "Produced Quantity" depending on the department (Standard vs. FP).
*   **Standard (Molding/Assembly)** relies on aggregated data from MDW (`GoodQuantityMonitoring` / `IndicatorValueEmulation`).
*   **FP** relies on granular operation-level data from STAIN (`tb_odp_QuantitaProdotteOperazioni`).