<?php $__env->startSection('title', 'WELCOME'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card shadow mb-4 mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col text-center p-5">
                    <h1>Benvenuto in GualaApp</h1>
                    <img src="<?php echo e(asset('images/logo.jpg')); ?>" class="img-fluid my-5">
                </div>
            </div>


            <?php if(!Auth::user()->admin): ?>

                <div class="row">

                    <?php
                        $pivot = "";
                    ?>

                    <?php $__currentLoopData = Auth::user()->active_apps()->orderby("site_id")->orderby("name_en")->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $active_app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($pivot != $active_app->site->name): ?>
                            <?php $pivot = $active_app->site->name; ?>

                            <div class="col-12 my-3">
                                <h5>
                                    <?php echo e($active_app->site->name); ?>

                                </h5>
                            </div>
                        <?php endif; ?>

                        <div class="col-4 my-3">
                            <div class="info-box" onclick="location.href='<?php echo e($active_app->code); ?>'">
                                <span class="info-box-icon bg-info"><img src="<?php echo e($active_app->icon_link); ?>" alt="Icon" class="w-100 img-fluid img-thumbnail"></span>
                                <div class="info-box-content">
                                    <h4 class="info-box-text"><?php echo e($active_app->name); ?></h4>
                                   <!--  <span class="info-box-number">Alert: 0</span> -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\guala\guala-app\resources\views/home.blade.php ENDPATH**/ ?>