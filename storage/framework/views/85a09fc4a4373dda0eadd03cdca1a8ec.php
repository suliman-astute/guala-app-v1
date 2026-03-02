<form id="async" method="POST" action="<?php echo e(route('gestione_piovan.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo e($gestione_piovan->id); ?>">

    <div class="form-group">
        <label for="endpoint">Endpoint</label>
        <input type="text" class="form-control" name="endpoint" value="<?php echo e(old('endpoint', $gestione_piovan->endpoint)); ?>" required>
    </div>

    <div class="form-group">
        <label for="chiamata_soap">Soap Action</label>
        <input type="text" class="form-control" name="chiamata_soap" value="<?php echo e(old('chiamata_soap', $gestione_piovan->chiamata_soap)); ?>" required>
    </div>
    <div class="form-group mb-4">
        <label class="label label-primary">Azienda</label>
        <select class="form-control form-control-sm" name="azienda">
            <option value="0" <?php echo e($gestione_piovan->azienda == '' ? 'selected' : ''); ?>></option>
            <?php $__currentLoopData = $aziende; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" <?php echo e($gestione_piovan->azienda == $id ? 'selected' : ''); ?>>
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
                url: "<?php echo e(route('gestione_piovan.store')); ?>",
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
<?php /**PATH C:\xampp\htdocs\guala\guala-app\resources\views/gestione_piovan/form.blade.php ENDPATH**/ ?>