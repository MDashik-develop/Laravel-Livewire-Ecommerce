<?php if(request()->routeIs('backend.*') || request()->is('dashboard*')): ?>
    <?php if (isset($component)) { $__componentOriginal5b3e50100890554846dee48a686a1c5c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b3e50100890554846dee48a686a1c5c = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App\Backend::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app.backend'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App\Backend::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title ?? null)]); ?>
        <?php if (isset($component)) { $__componentOriginal95c5505ccad18880318521d2bba3eac7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal95c5505ccad18880318521d2bba3eac7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::main','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::main'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php echo e($slot); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal95c5505ccad18880318521d2bba3eac7)): ?>
<?php $attributes = $__attributesOriginal95c5505ccad18880318521d2bba3eac7; ?>
<?php unset($__attributesOriginal95c5505ccad18880318521d2bba3eac7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal95c5505ccad18880318521d2bba3eac7)): ?>
<?php $component = $__componentOriginal95c5505ccad18880318521d2bba3eac7; ?>
<?php unset($__componentOriginal95c5505ccad18880318521d2bba3eac7); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b3e50100890554846dee48a686a1c5c)): ?>
<?php $attributes = $__attributesOriginal5b3e50100890554846dee48a686a1c5c; ?>
<?php unset($__attributesOriginal5b3e50100890554846dee48a686a1c5c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b3e50100890554846dee48a686a1c5c)): ?>
<?php $component = $__componentOriginal5b3e50100890554846dee48a686a1c5c; ?>
<?php unset($__componentOriginal5b3e50100890554846dee48a686a1c5c); ?>
<?php endif; ?>
<?php else: ?>
    <?php if (isset($component)) { $__componentOriginald9d1edf4185d7585515fce74735fc215 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald9d1edf4185d7585515fce74735fc215 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App\Frontend::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app.frontend'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App\Frontend::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title ?? null)]); ?>
        <?php if (isset($component)) { $__componentOriginal95c5505ccad18880318521d2bba3eac7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal95c5505ccad18880318521d2bba3eac7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::main','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::main'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php echo e($slot); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal95c5505ccad18880318521d2bba3eac7)): ?>
<?php $attributes = $__attributesOriginal95c5505ccad18880318521d2bba3eac7; ?>
<?php unset($__attributesOriginal95c5505ccad18880318521d2bba3eac7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal95c5505ccad18880318521d2bba3eac7)): ?>
<?php $component = $__componentOriginal95c5505ccad18880318521d2bba3eac7; ?>
<?php unset($__componentOriginal95c5505ccad18880318521d2bba3eac7); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald9d1edf4185d7585515fce74735fc215)): ?>
<?php $attributes = $__attributesOriginald9d1edf4185d7585515fce74735fc215; ?>
<?php unset($__attributesOriginald9d1edf4185d7585515fce74735fc215); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald9d1edf4185d7585515fce74735fc215)): ?>
<?php $component = $__componentOriginald9d1edf4185d7585515fce74735fc215; ?>
<?php unset($__componentOriginald9d1edf4185d7585515fce74735fc215); ?>
<?php endif; ?>
<?php endif; ?><?php /**PATH D:\my_codes\code\my_projects_Ashik\Laravel-Ecommerce\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>