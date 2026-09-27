<?php
    $store = null;
    try {
        $store = \App\Models\Store::with('media')->first();
    } catch (\Throwable $e) {}
    $storeName = $store?->name ?: config('app.name', 'Laravel');
    $storeLogo = $store?->media?->url;
?>

<?php if($storeLogo): ?>
    <div class="flex aspect-square size-8 shrink-0 items-center justify-center rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-2xs p-0.5">
        <img src="<?php echo e($storeLogo); ?>" alt="<?php echo e($storeName); ?>" class="h-full w-full object-contain rounded-md">
    </div>
<?php else: ?>
    <div class="flex aspect-square size-8 shrink-0 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
        <?php if (isset($component)) { $__componentOriginal159d6670770cb479b1921cea6416c26c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal159d6670770cb479b1921cea6416c26c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-logo-icon','data' => ['class' => 'size-5 fill-current text-white dark:text-black']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 fill-current text-white dark:text-black']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $attributes = $__attributesOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__attributesOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $component = $__componentOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__componentOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
    </div>
<?php endif; ?>

<div class="ms-1.5 grid flex-1 text-start text-sm min-w-0">
    <span class="truncate leading-tight font-semibold text-gray-900 dark:text-white" title="<?php echo e($storeName); ?>">
        <?php echo e($storeName); ?>

    </span>
</div>
<?php /**PATH D:\my_codes\code\my_projects_Ashik\Laravel-Ecommerce\resources\views/components/app-logo.blade.php ENDPATH**/ ?>