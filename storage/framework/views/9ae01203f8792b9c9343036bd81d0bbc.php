<header wire:poll.5s class="py-6 container mx-auto px-4 sm:px-0">
   <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
      <!-- Logo -->
      <div class="col-span-12 md:col-span-2 flex justify-center md:justify-start">
         <a href="#" class="flex items-center space-x-2">
            <img src="https://placehold.co/40x40/34D399/FFFFFF?text=N" alt="Nest Logo" class="rounded-lg">
            <span class="text-3xl font-bold text-gray-800">Nest</span>
         </a>
      </div>

      <!-- Search -->
      <div class="col-span-12 md:col-span-6">
         <form class="flex border border-gray-300 rounded-md">
            <div class="flex items-center">
               <!-- Custom Searchable Select Component with Alpine (No memory leaks or stacked listeners on poll) -->
               <div class="relative whitespace-nowrap" x-data="{
                  open: false,
                  search: '',
                  selected: 'all',
                  selectedText: 'All Categories'
               }" @click.outside="open = false">
                  <!-- Select Toggle Button -->
                  <div @click="open = !open" class="flex items-center justify-between w-full pl-4 pr-3 py-3 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                     <span class="text-gray-800 font-semibold" x-text="selectedText">All Categories</span>
                     <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                     </svg>
                  </div>

                  <!-- Dropdown Panel -->
                  <div x-show="open" x-cloak class="absolute z-10 w-max mt-1 bg-white border border-gray-300 rounded-lg shadow-lg">
                     <div class="p-2 border-b border-gray-200">
                        <input x-model="search" type="text" placeholder="Search categories..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                     </div>

                     <!-- Options List -->
                     <ul class="max-h-60 overflow-y-auto">
                        <li @click="selected = 'all'; selectedText = 'All Categories'; open = false"
                            x-show="!search || 'all categories'.includes(search.toLowerCase())"
                            class="px-4 py-2 text-gray-700 hover:bg-indigo-500 hover:text-white cursor-pointer">
                            All Categories
                        </li>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categorys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                           <li wire:key="hdr-cat-<?php echo e($category->id); ?>"
                               @click="selected = '<?php echo e($category->id); ?>'; selectedText = '<?php echo e(addslashes($category->name)); ?>'; open = false"
                               x-show="!search || '<?php echo e(strtolower(addslashes($category->name))); ?>'.includes(search.toLowerCase())"
                               class="px-4 py-2 text-gray-700 hover:bg-indigo-500 hover:text-white cursor-pointer">
                               <?php echo e($category->name); ?>

                           </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                     </ul>
                  </div>

                  <input type="hidden" name="category" :value="selected">
               </div>
            </div>
            <input type="text" placeholder="Search for items..." class="w-full p-3 focus:outline-none">
            <button type="submit" class="p-3 cursor-pointer">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
               </svg>
            </button>
         </form>
      </div>

      <!-- Location & Actions -->
      <div class="col-span-12 md:col-span-4 flex justify-center md:justify-end items-center space-x-6 text-gray-600">
         <a href="#" class="flex items-center space-x-1 hover:text-emerald-500 relative cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
               stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-sm">Compare</span>
            <span class="absolute -top-2 -right-2 bg-emerald-400 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
         </a>
         <a href="#" class="flex items-center space-x-1 hover:text-emerald-500 relative cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
               stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="text-sm">Wishlist</span>
            <span class="absolute -top-2 -right-2 bg-emerald-400 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">6</span>
         </a>

         <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('partials.carts', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1714802331-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
         <a href="#" class="flex items-center space-x-1 hover:text-emerald-500 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
               stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-sm">Account</span>
         </a>
      </div>
   </div>
</header><?php /**PATH D:\my_codes\code\my_projects_Ashik\Laravel-Ecommerce\resources\views/livewire/layouts/frontend/header.blade.php ENDPATH**/ ?>