<form id="async" method="POST" action="<?php echo e(route('presse.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo e($presse->id); ?>">

    <div class="form-group">
        <label for="nome">Posizione</label>
        <input type="text" class="form-control" name="GUAPosition" value="<?php echo e(old('GUAPosition', $presse->GUAPosition)); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="nid_meid_messo">ID Mes</label>
        <input type="text" class="form-control" name="id_mes"
               value="<?php echo e(old('id_mes', $presse->id_mes)); ?>">
    </div>

    <div class="form-group mb-4">
        <label for="id_piovan">Id Piovan</label>
        <input type="string" class="form-control" name="id_piovan"
               value="<?php echo e(old('id_piovan', $presse->id_piovan)); ?>">
    </div>
    <div class="form-group mb-4">
        <label for="id_piovan">Ingressi Usati</label>
        <input type="string" class="form-control" name="ingressi_usati"
               value="<?php echo e(old('ingressi_usati', $presse->ingressi_usati)); ?>">
    </div>
    <div class="form-group mb-4">
        <label class="label label-primary">Azienda</label>
        <select class="form-control form-control-sm" name="azienda">
            <option value="0" <?php echo e($presse->azienda == '' ? 'selected' : ''); ?>></option>
            <?php $__currentLoopData = $aziende; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e($presse->azienda == $id ? 'selected' : ''); ?>>
                    <?php echo e($nome); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <button id="click-me" type="submit" class="d-none"></button>
</form>

<script>
    let initialState = null;

    $(document).ready(function () {
        initialState = $('#async').serialize();

        $('#async').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo e(route('presse.store')); ?>",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    if (res.success) {
                        $('#ModalManage').modal('hide');
                        Swal.fire("Salvato!", "Il turno è stato salvato correttamente.", "success");
                        loadGridData();
                    } else if (res.error) {
                        Swal.fire("Errore", res.error.join('<br>'), "error");
                    }
                },
                error: function (xhr) {
                    Swal.fire("Errore", "Errore durante il salvataggio", "error");
                }
            });
        });
    });
</script>
<?php /**PATH C:\xampp\htdocs\guala-app\resources\views/presse/form.blade.php ENDPATH**/ ?>