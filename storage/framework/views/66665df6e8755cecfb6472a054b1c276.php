<form id="async" method="POST">
    <?php echo csrf_field(); ?>

    <div class="row">
        <div class="col-12 mb-2">
            <label class="label label-primary">Name*</label>
            <h3><?php echo e($user->name); ?></h3>
            <input type="hidden" name="id" value="<?php echo e($user->id); ?>">
        </div>


        <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-12 mb-2">
            <h5><?php echo e($site->name); ?></h5>

            <!-- Pulsanti per selezione/disselezione -->
            <button type="button" class="btn btn-sm btn-success select-all" data-site-id="<?php echo e($site->id); ?>">
                Seleziona tutto
            </button>
            <button type="button" class="btn btn-sm btn-danger deselect-all" data-site-id="<?php echo e($site->id); ?>">
                Deseleziona tutto
            </button>
        </div>

        <?php $__currentLoopData = $site->active_apps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $active_app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-4 mb-2">
                <input id="active_app<?php echo e($active_app->id); ?>" type="checkbox" name="active_apps[]"
                    value="<?php echo e($active_app->id); ?>"
                    class="site-app-checkbox site-<?php echo e($site->id); ?>"
                    <?php echo e($user->active_apps->contains($active_app->id) ? 'checked' : ''); ?>>
                <label for="active_app<?php echo e($active_app->id); ?>" class="label label-primary">
                    <?php echo e($active_app->name); ?>

                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <button class="d-none" id="click-me"></button>
</form>
<script>
    $(document).ready(function () {
        $('.select-all').on('click', function () {
            let siteId = $(this).data('site-id');
            $('.site-' + siteId).prop('checked', true);
        });

        $('.deselect-all').on('click', function () {
            let siteId = $(this).data('site-id');
            $('.site-' + siteId).prop('checked', false);
        });
    });
</script>
<script>
    var initialState;

    $(function() {

        setTimeout(function() {
            initialState = $('#async').serialize();
        }, 100);
        $('#async').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '<?php echo e(route('users.store_active_apps')); ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.error) {
                        Array.from(data.error).forEach(item => {
                            toastr.error(item);
                        });
                    } else {
                        toastr.success('Data saved successfully');
                        $("#ModalManage").modal('hide');
                        $("#ModalManage .close").click();
                        loadGridData(gridApi);
                    }
                }
            });
        });

    });
</script>
<?php /**PATH C:\xampp\htdocs\guala\guala-app\resources\views/users/form_active_apps.blade.php ENDPATH**/ ?>