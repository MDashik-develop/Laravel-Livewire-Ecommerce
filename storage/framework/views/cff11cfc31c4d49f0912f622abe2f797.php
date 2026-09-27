<div>
    <!--[if BLOCK]><![endif]--><?php if(isset($banners) && $banners->count() > 0): ?>
        <!-- Hero Banner Slider (Ordered by Position) -->
        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2"
            x-data="{
                current: 0,
                total: <?php echo e($banners->count()); ?>,
                autoplayInterval: null,
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    if (this.total > 1) {
                        this.autoplayInterval = setInterval(() => {
                            this.next();
                        }, 5000);
                    }
                },
                stopAutoplay() {
                    if (this.autoplayInterval) {
                        clearInterval(this.autoplayInterval);
                    }
                },
                next() {
                    this.current = (this.current + 1) % this.total;
                },
                prev() {
                    this.current = (this.current - 1 + this.total) % this.total;
                },
                goTo(index) {
                    this.current = index;
                }
            }"
            @mouseenter="stopAutoplay()"
            @mouseleave="startAutoplay()">

            <div class="relative h-[220px] sm:h-[340px] md:h-[420px] lg:h-[480px] w-full rounded-3xl overflow-hidden shadow-xl bg-slate-900 border border-gray-100 dark:border-zinc-800">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $targetUrl = $banner->link;
                        if (!$targetUrl && $banner->product) {
                            $targetUrl = '#';
                        } elseif (!$targetUrl && $banner->category) {
                            $targetUrl = '#';
                        }
                    ?>

                    <div x-show="current === <?php echo e($index); ?>"
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 scale-105"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0 w-full h-full"
                        wire:key="frontend-banner-<?php echo e($banner->id); ?>">

                        <!--[if BLOCK]><![endif]--><?php if($targetUrl): ?>
                            <a href="<?php echo e($targetUrl); ?>" class="block w-full h-full relative group">
                        <?php else: ?>
                            <div class="w-full h-full relative group">
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if($banner->videoMedia): ?>
                                <video autoplay muted loop playsinline class="w-full h-full object-cover">
                                    <source src="<?php echo e($banner->videoMedia->url); ?>" type="<?php echo e($banner->videoMedia->mime_type ?: 'video/mp4'); ?>">
                                </video>
                            <?php elseif($banner->media): ?>
                                <img src="<?php echo e($banner->media->urls['large'] ?? $banner->media->url); ?>"
                                    alt="Promotional Banner"
                                    class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700 ease-out"
                                    loading="<?php echo e($index === 0 ? 'eager' : 'lazy'); ?>">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!-- Subtle gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/10 pointer-events-none"></div>

                        <!--[if BLOCK]><![endif]--><?php if($targetUrl): ?>
                            </a>
                        <?php else: ?>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                <!-- Prev / Next Navigation Arrows -->
                <!--[if BLOCK]><![endif]--><?php if($banners->count() > 1): ?>
                    <button type="button"
                        @click="prev()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 hover:bg-white dark:bg-black/50 dark:hover:bg-black/80 text-gray-800 dark:text-white backdrop-blur-md flex items-center justify-center shadow-lg transition-all transform hover:scale-110 z-10 cursor-pointer"
                        title="Previous Banner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <button type="button"
                        @click="next()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 hover:bg-white dark:bg-black/50 dark:hover:bg-black/80 text-gray-800 dark:text-white backdrop-blur-md flex items-center justify-center shadow-lg transition-all transform hover:scale-110 z-10 cursor-pointer"
                        title="Next Banner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Indicators Dots -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10 bg-black/30 backdrop-blur-sm px-3 py-1.5 rounded-full">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button"
                                @click="goTo(<?php echo e($index); ?>)"
                                class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                :class="current === <?php echo e($index); ?> ? 'w-6 bg-white shadow-sm' : 'w-2 bg-white/50 hover:bg-white/80'"
                                title="Go to banner <?php echo e($index + 1); ?>">
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(isset($featuredBanners) && $featuredBanners->count() > 0): ?>
        <!-- Featured Promotional Banners -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
            <div class="grid grid-cols-1 md:grid-cols-<?php echo e(min($featuredBanners->count(), 3)); ?> gap-6">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $featuredBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $fbUrl = $fb->link ?: ($fb->product ? '#' : ($fb->category ? '#' : null));
                    ?>
                    <div class="group relative rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 aspect-[16/7] md:aspect-[16/8] bg-slate-900 border border-gray-100 dark:border-zinc-800">
                        <!--[if BLOCK]><![endif]--><?php if($fbUrl): ?>
                            <a href="<?php echo e($fbUrl); ?>" class="block w-full h-full relative cursor-pointer">
                        <?php else: ?>
                            <div class="w-full h-full relative">
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php if($fb->videoMedia): ?>
                            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                                <source src="<?php echo e($fb->videoMedia->url); ?>" type="<?php echo e($fb->videoMedia->mime_type ?: 'video/mp4'); ?>">
                            </video>
                        <?php elseif($fb->media): ?>
                            <img src="<?php echo e($fb->media->urls['large'] ?? $fb->media->url); ?>"
                                alt="Featured Banner"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                loading="lazy">
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

                        <!--[if BLOCK]><![endif]--><?php if($fbUrl): ?>
                            </a>
                        <?php else: ?>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <section class="py-16 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-slate-800 mb-2">Featured Products</h2>
                <p class="text-lg text-gray-500">Amader collection-er sera product-gulo dekhe nin</p>
                <div class="mt-4 w-24 h-1 bg-indigo-600 mx-auto rounded"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('livewire.partials.products', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </section>

    <!--[if BLOCK]><![endif]--><?php if(isset($footerBanners) && $footerBanners->count() > 0): ?>
        <!-- Footer Promotional Banners -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $footerBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ftb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $ftbUrl = $ftb->link ?: ($ftb->product ? '#' : ($ftb->category ? '#' : null));
                ?>
                <div class="group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 h-40 sm:h-52 md:h-60 bg-slate-900 border border-gray-100 dark:border-zinc-800 my-4">
                    <!--[if BLOCK]><![endif]--><?php if($ftbUrl): ?>
                        <a href="<?php echo e($ftbUrl); ?>" class="block w-full h-full relative cursor-pointer">
                    <?php else: ?>
                        <div class="w-full h-full relative">
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><?php if($ftb->videoMedia): ?>
                        <video autoplay muted loop playsinline class="w-full h-full object-cover">
                            <source src="<?php echo e($ftb->videoMedia->url); ?>" type="<?php echo e($ftb->videoMedia->mime_type ?: 'video/mp4'); ?>">
                        </video>
                    <?php elseif($ftb->media): ?>
                        <img src="<?php echo e($ftb->media->urls['large'] ?? $ftb->media->url); ?>"
                            alt="Footer Banner"
                            class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700 ease-out"
                            loading="lazy">
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/10 pointer-events-none"></div>

                    <!--[if BLOCK]><![endif]--><?php if($ftbUrl): ?>
                        </a>
                    <?php else: ?>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH D:\my_codes\code\my_projects_Ashik\Laravel-Ecommerce\resources\views/livewire/forntend/home/index.blade.php ENDPATH**/ ?>