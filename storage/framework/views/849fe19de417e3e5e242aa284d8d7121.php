<form id="async" method="POST" action="<?php echo e(route('gestione_turni.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo e(optional($gestione_turni)->id); ?>">
    <input type="hidden" name="id_capoturno" value="<?php echo e(Auth::user()->id); ?>">
    <?php
        $mappa = collect($gestione_turni['operatori_associabili'] ?? []);
        // Normalizzo il valore da preselezionare (edit + old input)
        $raw = old('id_operatori', $gestione_turni->id_operatori ?? []);

        if (is_array($raw)) {
            $selezionati = array_map('strval', $raw);
        } elseif (is_string($raw) && \Illuminate\Support\Str::startsWith($raw, '[')) {
            // JSON tipo "[3,4]"
            $selezionati = array_map('strval', json_decode($raw, true) ?? []);
        } elseif (is_string($raw) && str_contains($raw, ',')) {
            // CSV tipo "3,4"
            $selezionati = array_map('strval', array_filter(array_map('trim', explode(',', $raw))));
        } elseif ($raw !== null && $raw !== '') {
            // singolo valore
            $selezionati = [(string)$raw];
        } else {
            $selezionati = [];
        }

        // MAPPA [id => nome] dei macchinari (fallback: passata esplicitamente o attaccata al model)
        $mappaMacchinari = collect(
            $gestione_turni['macchinari_associabili']
            ?? ($macchinari_associabili ?? [])
        );

        // Normalizzazione SELEZIONATI (multiplo) come per operatori
        $rawMac = old('id_macchinari_associati', $gestione_turni->id_macchinari_associati ?? []);

        if (is_array($rawMac)) {
            $selezionatiMac = array_map('strval', $rawMac);
        } elseif (is_string($rawMac) && \Illuminate\Support\Str::startsWith($rawMac, '[')) {
            // JSON tipo "[1,2]"
            $selezionatiMac = array_map('strval', json_decode($rawMac, true) ?? []);
        } elseif (is_string($rawMac) && str_contains($rawMac, ',')) {
            // CSV tipo "1,2"
            $selezionatiMac = array_map('strval', array_filter(array_map('trim', explode(',', $rawMac))));
        } elseif ($rawMac !== null && $rawMac !== '') {
            // singolo valore
            $selezionatiMac = [(string) $rawMac];
        } else {
            $selezionatiMac = [];
        }
    ?>
    <div class="mb-3">
        <input type="date"
            id="data_turno"
            name="data_turno"
            class="form-control"
            value="<?php echo e(old('data_turno', $gestione_turni->data_turno ? \Carbon\Carbon::parse($gestione_turni->data_turno)->format('Y-m-d') : now()->format('Y-m-d'))); ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Turno</label>
        <select name="id_turno" class="form-control">
            <option value="">-- scegli --</option>
            <option value="1" <?php if(old('id_turno', $gestione_turni->id_turno ?? null)==1): echo 'selected'; endif; ?>>Turno 1</option>
            <option value="2" <?php if(old('id_turno', $gestione_turni->id_turno ?? null)==2): echo 'selected'; endif; ?>>Turno 2</option>
            <option value="3" <?php if(old('id_turno', $gestione_turni->id_turno ?? null)==3): echo 'selected'; endif; ?>>Turno 3</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Operatori</label>

        <select name="id_operatori[]" id="id_operatori" class="form-select" multiple>v
            <?php $__currentLoopData = $mappa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e(in_array((string)$id, $selezionati, true) ? 'selected' : ''); ?>>
                <?php echo e($nome); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
   
    <div class="mb-3">
        <label class="form-label">Macchinario</label>
        <select name="id_macchinari_associati[]" id="id_macchinari_associati" class="form-select" multiple>
            <?php $__currentLoopData = $mappaMacchinari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e(in_array((string)$id, $selezionatiMac, true) ? 'selected' : ''); ?>>
                    <?php echo e($nome); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
 
    <button id="click-me" type="submit" class="d-none"></button>
</form>

<script>

    function initChoicesInModal() {
        const modal = document.getElementById('ModalManage');

        const op = modal?.querySelector('#id_operatori');
        if (op) {
            if (op._choices) op._choices.destroy();      // re-init sicuro
            op._choices = new Choices(op, {
            removeItemButton: true,
            placeholder: true,
            placeholderValue: 'Seleziona operatori…',
            searchEnabled: true,
            searchPlaceholderValue: 'Cerca…',
            shouldSort: true,
            allowHTML: false
            });
        }

        const mac = modal?.querySelector('#id_macchinari_associati');
        if (mac) {
            if (mac._choices) mac._choices.destroy();
            mac._choices = new Choices(mac, {
            searchEnabled: true,
            shouldSort: true,
            allowHTML: false
            });
        }
    }

    document.addEventListener('shown.bs.modal', function (e) {
    if (e.target.id === 'ModalManage') initChoicesInModal();
    });

    $(function () {
        $('#async').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo e(route('gestione_turni.store')); ?>",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    if (res.success) {
                        $('#ModalManage').modal('hide');
                        Swal.fire("Salvato!", "Il turno è stato salvato correttamente.", "success");
                        if (typeof loadGridData === 'function') loadGridData();
                    } else if (res.error) {
                        Swal.fire("Errore", Array.isArray(res.error) ? res.error.join('<br>') : res.error, "error");
                    }
                },
                error: function () {
                    Swal.fire("Errore", "Errore durante il salvataggio", "error");
                }
            });
        });
    });
</script>
<?php /**PATH C:\xampp\htdocs\guala\guala-app\resources\views/gestione_turni/form.blade.php ENDPATH**/ ?>