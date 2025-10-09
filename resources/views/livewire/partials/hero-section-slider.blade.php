<div 
    wire:ignore
    x-data
    x-init="
        let init = () => {
            if (typeof Swiper === 'undefined') {
                document.addEventListener('swiper:ready', () => init());
                return;
            }
            if ($el.swiper) return; // prevent double init
            
            new Swiper($el, {
                modules: [SwiperModules.Navigation, SwiperModules.Pagination],
                loop: true,
                pagination: {
                    el: $el.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                navigation: {
                    nextEl: $el.querySelector('.swiper-button-next'),
                    prevEl: $el.querySelector('.swiper-button-prev'),
                },
            });
        };

        init();

        // Re-init when Livewire lazy loads or navigates

        

        Livewire.hook('morph.added', (el) => {
            // ✅ Only run if 'el' is an HTMLElement
            if (el instanceof HTMLElement && el.contains($el)) {
                init();
            }
        });
    "
    class="swiper"
>
    <div class="swiper-wrapper">
        <div class="swiper-slide bg-amber-50 p-12 rounded-xl">Slide 1</div>
        <div class="swiper-slide border">Slide 2</div>
        <div class="swiper-slide border">Slide 3</div>
    </div>

    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
