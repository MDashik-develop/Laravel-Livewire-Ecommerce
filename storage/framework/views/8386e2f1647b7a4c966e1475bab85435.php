<?php if (isset($component)) { $__componentOriginal5b3e50100890554846dee48a686a1c5c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b3e50100890554846dee48a686a1c5c = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App\Backend::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app.backend'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App\Backend::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><div>Test</div> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b3e50100890554846dee48a686a1c5c)): ?>
<?php $attributes = $__attributesOriginal5b3e50100890554846dee48a686a1c5c; ?>
<?php unset($__attributesOriginal5b3e50100890554846dee48a686a1c5c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b3e50100890554846dee48a686a1c5c)): ?>
<?php $component = $__componentOriginal5b3e50100890554846dee48a686a1c5c; ?>
<?php unset($__componentOriginal5b3e50100890554846dee48a686a1c5c); ?>
<?php endif; ?><?php /**PATH D:\my_codes\code\my_projects_Ashik\Laravel-Ecommerce\storage\framework\views/53af675c433b7d763a0a96dee0948023.blade.php ENDPATH**/ ?>