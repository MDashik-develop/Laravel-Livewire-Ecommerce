<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title><?php echo e($title ?? config('app.name')); ?></title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<?php echo app('flux')->fluxAppearance(); ?>


<style>
    dialog:not([open]) {
        display: none !important;
        pointer-events: none !important;
    }

    /* Keep hidden elements hidden, respecting Livewire and Alpine */
    [x-cloak],
    [hidden],
    .hidden,
    [style*="display: none"],
    [style*="display:none"] {
        display: none !important;
    }

    /* Force all admin buttons to keep icon and text in a single horizontal row */
    button,
    [data-flux-button],
    .btn {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        white-space: nowrap !important;
        vertical-align: middle;
    }

    button.text-start,
    button.justify-start,
    [data-flux-menu-item],
    [data-flux-profile] {
        justify-content: flex-start !important;
    }

    /* Flux button inner slot container when slot contains SVG & text */
    [data-flux-button] > span:not([hidden]):not([style*="display: none"]) {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        white-space: nowrap !important;
        gap: 0.5rem !important;
    }

    /* Keep all SVGs in buttons inline and non-shrinking */
    button svg,
    [data-flux-button] svg {
        flex-shrink: 0 !important;
        display: inline-block !important;
        vertical-align: middle !important;
    }
</style>
<?php /**PATH D:\my_codes\code\my_projects_Ashik\Laravel-Ecommerce\resources\views/partials/head.blade.php ENDPATH**/ ?>