<?php $__env->startSection('title', "Gestione Turni Presse"); ?>

<?php $__env->startSection('content_header'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('gestione_turni_presse.table', ['page' => 'Gestione Turni Presse'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\guala\guala-app\resources\views/app/APP3/index.blade.php ENDPATH**/ ?>