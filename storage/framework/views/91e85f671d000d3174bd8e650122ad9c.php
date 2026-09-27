<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest - Apnar Proyojoner Dokan</title>
    

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">



        

    
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo app('flux')->fluxAppearance(); ?>


    <style>
        /* Custom styles */
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f8fafc; /* slate-50 */
        }
    </style>
    
</head>
<body class="antialiased">

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layouts.frontend.header-top', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4104470772-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layouts.frontend.header', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4104470772-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layouts.frontend.header-nav', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4104470772-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>







    <main class="py-8 container mx-auto px-4 sm:px-0">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full md:w-1/5 space-y-6">
                <!-- Categories --> 
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layouts.frontend.aside-categorys', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4104470772-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                <!-- Price Filter -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-xl font-bold mb-4 text-gray-800">Fill by price</h3>
                    <input type="range" min="364" max="1000" value="500" class="range-slider out-of-range:border-red-500">
                    <div class="flex justify-between text-sm text-gray-500 mt-2">
                        <span>From: <span class="font-bold text-emerald-500">$364</span></span>
                        <span>To: <span class="font-bold text-emerald-500">$1,000</span></span>
                    </div>
                </div>
            </aside>

            <!-- Hero Section -->
            <div class="w-full md:w-4/5 border border-gray-200 rounded-lg shadow-sm">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('partials.hero-section-slider', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4104470772-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        </div>
        <!-- Popular Products -->
        <div class="mt-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Popular Products</h2>
                <div class="flex space-x-6 text-gray-600">
                    <a href="#" class="hover:text-emerald-500 font-semibold text-emerald-500">All</a>
                    <a href="#" class="hover:text-emerald-500 font-semibold">Milks & Dairies</a>
                    <a href="#" class="hover:text-emerald-500 font-semibold">Coffes & Teas</a>
                    <a href="#" class="hover:text-emerald-500 font-semibold">Pet Foods</a>
                    <a href="#" class="hover:text-emerald-500 font-semibold">Meats</a>
                    <a href="#" class="hover:text-emerald-500 font-semibold">Vegetables</a>
                    <a href="#" class="hover:text-emerald-500 font-semibold">Fruits</a>
                </div>
            </div>
            <!-- Product grid would go here -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <!-- Placeholder for product cards -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 text-center animate-pulse">
                    <div class="bg-gray-200 h-40 rounded-md mb-4"></div>
                    <div class="bg-gray-200 h-4 w-3/4 mx-auto mb-2 rounded"></div>
                    <div class="bg-gray-200 h-4 w-1/2 mx-auto rounded"></div>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 text-center animate-pulse">
                    <div class="bg-gray-200 h-40 rounded-md mb-4"></div>
                    <div class="bg-gray-200 h-4 w-3/4 mx-auto mb-2 rounded"></div>
                    <div class="bg-gray-200 h-4 w-1/2 mx-auto rounded"></div>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 text-center animate-pulse">
                    <div class="bg-gray-200 h-40 rounded-md mb-4"></div>
                    <div class="bg-gray-200 h-4 w-3/4 mx-auto mb-2 rounded"></div>
                    <div class="bg-gray-200 h-4 w-1/2 mx-auto rounded"></div>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 text-center animate-pulse">
                    <div class="bg-gray-200 h-40 rounded-md mb-4"></div>
                    <div class="bg-gray-200 h-4 w-3/4 mx-auto mb-2 rounded"></div>
                    <div class="bg-gray-200 h-4 w-1/2 mx-auto rounded"></div>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 text-center animate-pulse">
                    <div class="bg-gray-200 h-40 rounded-md mb-4"></div>
                    <div class="bg-gray-200 h-4 w-3/4 mx-auto mb-2 rounded"></div>
                    <div class="bg-gray-200 h-4 w-1/2 mx-auto rounded"></div>
                </div>
            </div>
        </div>
        <section>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('utilities.toast-modal', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4104470772-5', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php echo e($slot); ?>

        </section>
    </main>









    
    
    <!-- Footer Section -->
    <footer class="bg-slate-900 text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-2xl font-bold text-indigo-400 mb-4">ShopNest</h3>
                    <p class="text-gray-400">Desh-er shobcheye bishshosto online shop. Amra quality product o uttom seba dite protishrutiboddho.</p>
                </div>
                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                <!-- Categories -->
                <div>
                    <h4 class="font-semibold text-lg mb-4">Categories</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Men's Fashion</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Women's Fashion</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Electronics</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Home & Kitchen</a></li>
                    </ul>
                </div>
                <!-- Newsletter -->
                <div>
                    <h4 class="font-semibold text-lg mb-4">Newsletter</h4>
                    <p class="text-gray-400 mb-4">Subscribe korun notun offer o update pete.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email" class="w-full rounded-l-lg py-2 px-4 text-gray-800 outline-none">
                        <button class="bg-indigo-600 text-white font-semibold px-4 rounded-r-lg hover:bg-indigo-700">Go</button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500">
                &copy; 2025 ShopNest. All Rights Reserved.
            </div>
        </div>
    </footer>


        <?php app('livewire')->forceAssetInjection(); ?>
<?php echo app('flux')->scripts(); ?>

        <?php echo $__env->yieldPushContent('cdn-end'); ?>
        <?php echo $__env->yieldPushContent('scripts-end'); ?>
        <?php echo $__env->yieldPushContent('styles-end'); ?>
        <?php echo $__env->yieldPushContent('scripts'); ?>
        

    

</body>
</html>





<?php /**PATH D:\my_codes\code\my_projects_Ashik\Laravel-Ecommerce\resources\views/components/layouts/app/frontend.blade.php ENDPATH**/ ?>