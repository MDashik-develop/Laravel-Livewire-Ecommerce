<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopNest - Apnar Proyojoner Dokan</title>
    

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance


    <style>
        /* Custom styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* slate-50 */
        }
        .nav-link {
            @apply text-gray-600 hover:text-indigo-600 transition-colors duration-300;
        }
        .btn-primary {
            @apply bg-indigo-600 text-white font-semibold py-3 px-8 rounded-lg hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-md;
        }
        .card {
            @apply bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 border border-slate-200;
        }
        .card-badge {
            @apply absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full;
        }
    </style>
</head>
<body class="antialiased">


    <!-- Header Section -->
    <header class="bg-white/80 backdrop-blur-lg sticky top-0 z-40 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="#" class="text-3xl font-bold text-indigo-600">
                    Shop<span class="text-slate-800">Nest</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#" class="nav-link font-semibold">Home</a>
                    <a href="#" class="nav-link">Shop</a>
                    <a href="#" class="nav-link">Categories</a>
                    <a href="#" class="nav-link">About Us</a>
                    <a href="#" class="nav-link">Contact</a>
                </nav>

                <!-- Icons and Cart -->
                <div class="flex items-center space-x-5">
                    <a href="#" class="text-gray-600 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </a>

                    <livewire:partials.carts />
                    
                    <a href="#" class="hidden sm:block text-gray-600 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </a>
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>
             <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <a href="#" class="block py-2 px-4 text-sm nav-link">Home</a>
                <a href="#" class="block py-2 px-4 text-sm nav-link">Shop</a>
                <a href="#" class="block py-2 px-4 text-sm nav-link">Categories</a>
                <a href="#" class="block py-2 px-4 text-sm nav-link">About Us</a>
                <a href="#" class="block py-2 px-4 text-sm nav-link">Contact</a>
            </div>
        </div>
    </header>

    <main>
        <livewire:utilities.toast-modal />
        {{ $slot }}
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


        @fluxScripts


</body>
</html>





