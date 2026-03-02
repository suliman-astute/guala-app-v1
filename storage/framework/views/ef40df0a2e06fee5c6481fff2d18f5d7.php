<form id="async" method="POST" action="<?php echo e(route('codice_oggetto.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo e($codice_oggetto->id); ?>">

    <div class="form-group">
        <label for="codici">Codici</label>
        <input type="text" class="form-control" name="codici" value="<?php echo e(old('codici', $codice_oggetto->codici)); ?>" required>
    </div>

    <div class="form-group">
        <label for="oggetto">Oggetto</label>
        <input type="text" class="form-control" name="oggetto" value="<?php echo e(old('oggetto', $codice_oggetto->oggetto)); ?>" required>
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
                url: "<?php echo e(route('codice_oggetto.store')); ?>",
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
<?php /**PATH C:\xampp\htdocs\guala\guala-app\resources\views/codici_oggetto/form.blade.php ENDPATH**/ ?>