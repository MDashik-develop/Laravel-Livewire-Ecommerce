<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

{{--     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script> --}}

        {{-- <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script> --}}

    {{-- @stack('cdn') --}}
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance

    <style>
        /* Custom styles */
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f8fafc; /* slate-50 */
        }
    </style>
    
</head>
<body class="antialiased">

    <livewire:layouts.frontend.header-top />
    <livewire:layouts.frontend.header />
    <livewire:layouts.frontend.header-nav />







    <main class="py-8 container mx-auto px-4 sm:px-0">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full md:w-1/5 space-y-6">
                <!-- Categories --> 
                <livewire:layouts.frontend.aside-categorys />
                <!-- Price Filter -->
                <livewire:layouts.frontend.aside-filter />
            </aside>

            <!-- Hero Section -->
            <div class="w-full md:w-4/5 rounded-2xl overflow-hidden shadow-sm">
                <livewire:partials.hero-section-slider />
            </div>
        </div>
        <!-- Popular Products -->
        <div class="mt-12">
            <livewire:forntend.popular-products />
        </div>
        <section>
            <livewire:utilities.toast-modal />
            {{ $slot }}
        </section>
    </main>









    {{-- <main>
        <livewire:utilities.toast-modal />
        {{ $slot }}
    </main> --}}
    
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

    @fluxScripts
    @stack('cdn-end')
    @stack('scripts-end')
    @stack('styles-end')
    @stack('scripts')
</body>
</html>





