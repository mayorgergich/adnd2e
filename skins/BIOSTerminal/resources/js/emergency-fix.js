// Emergency fix for interaction issues
(function() {
    // Execute immediately
    console.log("Emergency fix executing immediately...");
    
    // Fix 1: Remove loading classes immediately
    document.documentElement.classList.remove('js-loading');
    
    // Also wait for DOM to be ready for additional fixes
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Emergency fix DOM loaded phase...");
        
        // Add js-loaded class
        document.body.classList.add('js-loaded');
        
        // Fix 2: Add emergency styles
        const style = document.createElement('style');
        style.textContent = `
            html, body, * {
                visibility: visible !important;
                opacity: 1 !important;
                pointer-events: auto !important;
            }
            
            .dos-wrapper {
                display: flex !important;
            }
            
            .boot-sequence {
                display: none !important;
            }
            
            a, button, input, .mw-editsection, .mw-editsection-like {
                cursor: pointer !important;
                pointer-events: auto !important;
            }
        `;
        document.head.appendChild(style);
        
        // Fix 3: Make sure content is displayed
        const wrapper = document.querySelector('.dos-wrapper');
        if (wrapper) {
            wrapper.style.display = 'flex';
            console.log("Set wrapper to display:flex");
        }
        
        // Fix 4: Fix all links explicitly
        document.querySelectorAll('a').forEach(link => {
            link.style.pointerEvents = 'auto';
            link.style.cursor = 'pointer';
        });
        
        console.log("Emergency fix applied successfully");
    });
})();
