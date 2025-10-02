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
               


               <!-- Custom Searchable Select Component -->
               <div id="searchable-select-container" class="relative whitespace-nowrap">
                     
                     <!-- This div acts as the button to open/close the dropdown -->
                     <div id="select-button" class="flex items-center justify-between w-full pl-4 pr-3 py-3 rounded-lg  cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <span id="selected-value" class="text-gray-800 font-semibold">All Categories</span>
                        <!-- Arrow icon that will rotate -->
                        <svg id="arrow-icon" class="w-5 h-5 text-gray-500 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                           <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                     </div>

                     <!-- This is the dropdown panel that contains the search input and options -->
                     <div id="dropdown-panel" class="absolute z-10 w-max mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                        <!-- Search input field -->
                        <div class="p-2 border-b border-gray-200">
                           <input id="search-input" type="text" placeholder="Search categories..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        
                        <!-- List of options -->
                        <ul id="options-list" class="max-h-60 overflow-y-auto">
                           <!-- Options are list items -->
                           <li class="px-4 py-2 text-gray-700 hover:bg-indigo-500 hover:text-white cursor-pointer" data-value="all">All Categories</li>
                           @foreach ($categorys as $category) 
                              <li class="px-4 py-2 text-gray-700 hover:bg-indigo-500 hover:text-white cursor-pointer" data-value="$category">{{ $category->name }}</li>
                           @endforeach
                        </ul>
                     </div>
               </div>
               <!-- You can use a hidden input to store the actual value for form submissions -->
               <input type="hidden" name="category" id="hidden-select-value" value="all">



            </div>
            <input type="text" placeholder="Search for items..." class="w-full p-3 focus:outline-none">
            <button type="submit" class="p-3">
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
         
         <a href="#" class="flex items-center space-x-1 hover:text-emerald-500 relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
               stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-sm">Compare</span>
            <span
               class="absolute -top-2 -right-2 bg-emerald-400 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
         </a>
         <a href="#" class="flex items-center space-x-1 hover:text-emerald-500 relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
               stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="text-sm">Wishlist</span>
            <span
               class="absolute -top-2 -right-2 bg-emerald-400 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">6</span>
         </a>

         <livewire:partials.carts />
         <a href="#" class="flex items-center space-x-1 hover:text-emerald-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
               stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-sm">Account</span>
         </a>
      </div>
   </div>

   
    <script>
        // Get all the necessary HTML elements
        const selectContainer = document.getElementById('searchable-select-container');
        const selectButton = document.getElementById('select-button');
        const selectedValueSpan = document.getElementById('selected-value');
        const dropdownPanel = document.getElementById('dropdown-panel');
        const searchInput = document.getElementById('search-input');
        const optionsList = document.getElementById('options-list');
        const allOptions = optionsList.querySelectorAll('li');
        const arrowIcon = document.getElementById('arrow-icon');
        const hiddenInput = document.getElementById('hidden-select-value');

        // --- Event Listeners ---

        // 1. Toggle dropdown visibility when the button is clicked
        selectButton.addEventListener('click', (e) => {
            // Stop the click from bubbling up to the window
            e.stopPropagation(); 
            dropdownPanel.classList.toggle('hidden');
            arrowIcon.classList.toggle('rotate-180');
        });

        // 2. Filter options based on search input
        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();
            allOptions.forEach(option => {
                const optionText = option.textContent.toLowerCase();
                // If the option text includes the search term, show it. Otherwise, hide it.
                if (optionText.includes(searchTerm)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        });

        // 3. Handle option selection
        allOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Update the displayed value
                selectedValueSpan.textContent = option.textContent;
                // Update the hidden input's value
                hiddenInput.value = option.dataset.value;
                
                // Close the dropdown
                dropdownPanel.classList.add('hidden');
                arrowIcon.classList.remove('rotate-180');
                
                // Optional: Log the selected value to the console
                console.log('Selected Category:', hiddenInput.value);
            });
        });

        // 4. Close the dropdown if the user clicks anywhere outside of it
        window.addEventListener('click', (e) => {
            if (!selectContainer.contains(e.target)) {
                dropdownPanel.classList.add('hidden');
                arrowIcon.classList.remove('rotate-180');
            }
        });
    </script>
</header>