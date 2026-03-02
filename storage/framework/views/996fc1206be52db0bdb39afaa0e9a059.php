<form id="async" method="POST" action="<?php echo e(route('aziende.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo e($aziende->id); ?>">

    <div class="form-group">
        <label for="nome">Nome Azienda</label>
        <input type="text" class="form-control" name="nome" value="<?php echo e(old('nome', $aziende->nome)); ?>" required>
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
                url: "<?php echo e(route('aziende.store')); ?>",
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
<?php /**PATH C:\xampp\htdocs\guala-app\resources\views/aziende/form.blade.php ENDPATH**/ ?>