<form id="async" method="POST" action="<?php echo e(route('stamping.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo e(optional($macchina)->id); ?>">

    <div class="form-group mb-3">
        <label for="name">Nome</label>
        <input type="text" class="form-control" name="name"
               value="<?php echo e(old('name', optional($macchina)->name)); ?>" required>
    </div>

    <!-- <div class="form-group mb-3">
        <label for="GUAPosition">GUA Position</label>
        <input type="number" class="form-control" name="GUAPosition"
               value="<?php echo e(old('GUAPosition', optional($macchina)->GUAPosition)); ?>">
    </div> -->

    <div class="form-group mb-3">
        <label for="no">NO</label>
        <input type="text" class="form-control" name="no"
               value="<?php echo e(old('no', optional($macchina)->no)); ?>">
    </div>
    <div class="form-group mb-4">
        <label class="label label-primary">Azienda</label>
        <select class="form-control form-control-sm" name="azienda">
            <option value="0" <?php echo e($macchina->azienda == '' ? 'selected' : ''); ?>></option>
            <?php $__currentLoopData = $aziende; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e($macchina->azienda == $id ? 'selected' : ''); ?>>
                    <?php echo e($nome); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <button id="click-me" type="submit" class="d-none"></button>
</form>

<script>
    $(function () {
        $('#async').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo e(route('stamping.store')); ?>",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    if (res.success) {
                        $('#ModalManage').modal('hide');
                        Swal.fire("Salvato!", "La macchina è stata salvata correttamente.", "success");
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
<?php /**PATH C:\xampp\htdocs\guala-app\resources\views/stamping/form.blade.php ENDPATH**/ ?>