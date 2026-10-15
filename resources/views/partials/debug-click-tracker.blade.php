<script>
    (function() {
        console.log('%c🚀 [ShopNest Debug Tracker] Active - monitoring button clicks and Livewire state', 'background: #2563eb; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold;');

        // Check Livewire loading status
        if (window.Livewire) {
            console.log('%c✅ Livewire is already present on window.', 'color: #10b981; font-weight: bold;');
        } else {
            console.log('%c⏳ Waiting for Livewire to load...', 'color: #f59e0b;');
        }

        document.addEventListener('livewire:init', function() {
            console.log('%c🎉 Livewire:init event fired! Livewire engine ready.', 'background: #10b981; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold;');
            
            if (window.Livewire && window.Livewire.hook) {
                Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                    const calls = commit.calls && commit.calls.length 
                        ? commit.calls.map(c => `${c.method}(${JSON.stringify(c.params)})`).join(', ') 
                        : 'commit';
                    console.log(`%c⚡ [Livewire Request] Component: "${component.name}" | Action: ${calls}`, 'color: #8b5cf6; font-weight: bold;');
                    
                    succeed(({ snapshot, effect }) => {
                        console.log(`%c✨ [Livewire Response] Component: "${component.name}" updated successfully!`, 'color: #10b981; font-weight: bold;');
                    });

                    fail(() => {
                        console.error(`%c❌ [Livewire Error] Component: "${component.name}" request failed!`, 'color: #ef4444; font-weight: bold;');
                    });
                });
            }
        });

        // Global Click Interceptor (runs in capture phase so it catches EVERY click first)
        window.addEventListener('click', function(e) {
            const clickable = e.target.closest('button, a, [role="button"], input[type="button"], input[type="submit"], [wire\\:click]');
            
            let name = 'Unknown Element';
            let wireClick = null;
            let tagName = e.target.tagName.toLowerCase();

            if (clickable) {
                tagName = clickable.tagName.toLowerCase();
                wireClick = clickable.getAttribute('wire:click');
                const innerText = (clickable.innerText || clickable.textContent || '').replace(/\s+/g, ' ').trim();
                const title = clickable.getAttribute('title') || clickable.getAttribute('aria-label');
                const id = clickable.id ? `#${clickable.id}` : '';
                
                name = innerText || title || wireClick || (clickable.className ? `.${clickable.className.split(' ')[0]}` : '') || id || `<${tagName}>`;
            } else {
                name = (e.target.innerText || e.target.textContent || '').replace(/\s+/g, ' ').trim();
                if (!name || name.length > 30) {
                    name = `<${e.target.tagName.toLowerCase()}${e.target.id ? ' id="'+e.target.id+'"' : ''}>`;
                }
            }

            console.groupCollapsed(`%c🖱️ [BUTTON CLICKED] "${name}"`, 'background: #059669; color: white; padding: 3px 8px; border-radius: 4px; font-weight: bold;');
            console.log('📌 Clicked Element:', e.target);
            console.log('🔘 Button/Link Container:', clickable || 'Clicked element is not inside a button or link');
            if (wireClick) {
                console.log(`%c⚡ wire:click: "${wireClick}"`, 'color: #db2777; font-weight: bold;');
                if (!window.Livewire) {
                    console.error('❌ CRITICAL: Livewire is NOT loaded! wire:click will NOT execute.');
                }
            }
            console.log('📍 Coordinates:', { x: e.clientX, y: e.clientY });
            console.log('🛡️ Element at point:', document.elementFromPoint(e.clientX, e.clientY));
            console.groupEnd();
        }, true);
    })();
</script>
