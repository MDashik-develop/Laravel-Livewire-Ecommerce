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
                <livewire:partials.hero-section-slider lazy />
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
        

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Add to Cart Functionality
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
            const cartCountElement = document.getElementById('cart-count');
            const toastElement = document.getElementById('toast');
            let cartCount = 0;

            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    cartCount++;
                    cartCountElement.textContent = cartCount;

                    // Show notification
                    toastElement.classList.remove('opacity-0', 'translate-x-10');
                    
                    // Hide notification after 3 seconds
                    setTimeout(() => {
                        toastElement.classList.add('opacity-0', 'translate-x-10');
                    }, 3000);
                });
            });
        });
    </script> --}}

</body>
</html>





