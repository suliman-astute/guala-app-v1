<form id="async" method="POST" action="<?php echo e(route('turni.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo e($turno->id); ?>">

    <div class="form-group">
        <label for="nome">Nome turno</label>
        <input type="text" class="form-control" name="nome_turno" value="<?php echo e(old('nome_turno', $turno->nome_turno)); ?>" required>
    </div>

    <div class="form-group">
        <label for="orario_inizio">Orario di inizio</label>
        <input type="number" class="form-control" name="inizio" value="<?php echo e(old('inizio', $turno->inizio)); ?>" required>
    </div>

    <div class="form-group">
        <label for="orario_fine">Orario di fine</label>
        <input type="number" class="form-control" name="fine" value="<?php echo e(old('fine', $turno->fine)); ?>" required>
    </div>
    <div class="form-group mb-4">
        <label class="label label-primary">Azienda</label>
        <select class="form-control form-control-sm" name="azienda">
            <option value="0" <?php echo e($turno->azienda == '' ? 'selected' : ''); ?>></option>
            <?php $__currentLoopData = $aziende; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e($turno->azienda == $id ? 'selected' : ''); ?>>
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
                url: "<?php echo e(route('turni.store')); ?>",
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
<?php /**PATH C:\xampp\htdocs\guala-app\resources\views/turni/form.blade.php ENDPATH**/ ?>