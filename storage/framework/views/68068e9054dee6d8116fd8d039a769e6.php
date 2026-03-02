<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title><?php echo e($title ?? 'Report'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    :root{
      --text:#111; --muted:#666; --line:#ddd; --head:#f6f7f9; --group1:#eef3ff; --group2:#f9fafb;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0}
    body{
      font-family: Arial, Helvetica, sans-serif;
      color:var(--text);
      background:#fff;
      line-height:1.35;
    }

    .wrap{
      max-width:1200px;
      margin:18px auto 32px;
      padding:0 16px;
    }

    .title{
      display:flex; align-items:center; justify-content:space-between;
      gap:16px; margin-bottom:14px;
    }
    .title h1{
      font-size:20px; margin:0;
    }
    .actions button{
      padding:8px 14px; border-radius:8px; border:1px solid #222; background:#222; color:#fff; cursor:pointer;
    }

    .meta{
      color:var(--muted); font-size:12px; margin-bottom:10px;
    }

    table{
      width:100%; border-collapse:collapse; border:1px solid var(--line);
    }
    thead th{
      background:var(--head);
      font-weight:700; text-align:left; padding:8px; border-bottom:1px solid var(--line);
      font-size:13px;
    }
    tbody td{
      padding:8px; border-top:1px solid var(--line); font-size:13px;
      vertical-align:top;
    }
    .right{text-align:right}

    /* Riga gruppo: family */
    .tr-family td{
      background:var(--group1);
      font-weight:700;
      border-top:2px solid #cbd5ff;
    }
    /* Riga gruppo: machine */
    .tr-machine td{
      background:var(--group2);
      font-weight:600;
    }

    .badge{
      display:inline-block; padding:2px 6px; border-radius:6px; border:1px solid #cbd5ff; color:#1e40af; font-size:11px;
    }

    a.link{ color:#1f4dd6; text-decoration:none; }
    a.link:hover{ text-decoration:underline; }

    .dot { height: 14px; width: 14px; border-radius: 50%; background-color: #ccc; margin: auto; }
    .dot.green { background-color: #28a745; }
    .dot.red { background-color: #dc3545; }
    .dot.yellow { background-color: #ffc107; }

    @media print {
      .actions{ display:none !important; }
      @page{ size: A4 portrait; margin: 14mm; }
      body{ font-size:11px; }
      thead th, tbody td{ padding:6px; font-size:11px; }
      *{
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        forced-color-adjust: none !important;
      }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="title">
      <h1><?php echo e($title ?? 'Report'); ?></h1>
      <div class="actions">
        <button onclick="window.print()">🖨️ Stampa</button>
      </div>
    </div>
    <div class="meta">
      Generato il <?php echo e(now()->format('d/m/Y H:i')); ?>

    </div>

    <table aria-describedby="tabella-report">
      <thead>
        <tr>
          <th style="width:16%">Seq</th>
          <th style="width:16%">Codice</th>
          <th style="width:34%">Descrizione</th>
          <th style="width:8%">Ordine MES</th>
          <th style="width:12%" class="right">Q.tà ordine</th>
          <th style="width:12%" class="right">Q.tà prodotta</th>
          <th style="width:12%" class="right">Q.tà residua</th>
          <th style="width:12%" class="right">Prod</th>
          <th style="width:12%" class="right">Stop</th>
          <th style="width:12%" class="right">Ok</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $righe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          
          <?php if(is_array($r) && ($r['is_group'] ?? false) && ($r['group_type'] ?? null) === 'family'): ?>
            <tr class="tr-family">
              <td colspan="10">
                Famiglia: <?php echo e($r['value']); ?>

              </td>
            </tr>

          
          <?php elseif(is_array($r) && ($r['is_group'] ?? false) && ($r['group_type'] ?? null) === 'machine'): ?>
            <tr class="tr-machine">
              <td colspan="10">
                Macchina: <?php echo e($r['groupLabel'] ?? $r['value']); ?>

                <?php if(isset($r['family'])): ?>
                  <span class="badge">Family: <?php echo e($r['family']); ?></span>
                <?php endif; ?>
              </td>
            </tr>
<?php ?>
          
          <?php else: ?>
            <?php
              $seq         = $r->relSequence            ?? '';
              $codice      = $r->itemNo            ?? '';
              $descrizione = $r->itemDescription    ?? '';
              $qtaRim      = (int) ($r->quantita_rimanente ?? 0);
              $qtaOrd      = (int) ($r->quantity ?? 0);
              $qtaProd      = (int) ($r->quantita_prodotta ?? 0);
              $orderNo     = $r->mesOrderNo         ?? '';
             $stActive     = $r->mesStatus === 'Active'?'dot yellow':'dot';
              $stComp     = $r->mesStatus === 'Complete' ? 'dot green':'dot';
              $st     = ($r->mesStatus === 'Stop' || $r->mesStatus === 'Pause')?'dot red':'dot';
            ?>
            <tr>
              <td><?php echo e($seq); ?></td>
              <td><?php echo e($codice); ?></td>
              <td><?php echo e($descrizione); ?></td>
              <td><?php echo e($orderNo); ?></td>
              <td class="right"><?php echo e(number_format($qtaOrd, 0, ',', '.')); ?></td>
              <td class="right"><?php echo e(number_format($qtaProd, 0, ',', '.')); ?></td>
              <td class="right"><?php echo e(number_format($qtaRim, 0, ',', '.')); ?></td>
              <td class="right"><div class='<?php echo e($stActive); ?>'></div></td>
              <td class="right"><div class='<?php echo e($st); ?>'></div></td>
              <td class="right"><div class='<?php echo e($stComp); ?>'></div></td>
             
            
            </tr>
          <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="6" style="text-align:center; color:var(--muted); padding:14px">Nessun dato disponibile</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\guala\guala-app\resources\views/app/APP1/PDF/index.blade.php ENDPATH**/ ?>