// Emergency fix for interaction issues and content box styling
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
            
            /* Content Box Fixes - Added to match main background */
            .dos-content-area,
            .mw-body-content,
            .mw-body-content table,
            .wikitable,
            .wikitable > tr > th,
            .wikitable > * > tr > th,
            .wikitable > tr > td,
            .wikitable > * > tr > td,
            #content table,
            .mw-parser-output table,
            th, td,
            div.mw-content-ltr table,
            .mw-parser-output div.panel,
            .mw-parser-output table.wikitable,
            #bodyContent table,
            #bodyContent div.panel,
            pre, code,
            .infobox,
            .infobox th,
            .infobox td,
            .navbox,
            .navbox th,
            .navbox td,
            .vertical-navbox,
            .vertical-navbox th,
            .vertical-navbox td,
            #bodyContent, 
            #bodyContent > *,
            .mw-body, 
            .mw-body > * {
              background-color: #000080 !important; /* Hard-coded blue background */
              color: #FFFFFF !important; /* Hard-coded white text */
              border-color: #C0C0C0 !important; /* Hard-coded light gray border */
            }
            
            /* Ensure form elements remain visible */
            input, select, textarea {
              background-color: #000060 !important; /* Slightly darker blue */
              color: #FFFFFF !important;
              border: 1px solid #FFFFFF !important;
            }
            
            /* Make sure links remain visible against blue */
            a, a:visited {
              color: #00FF00 !important; /* Bright green for links */
            }
            
            a:hover {
              color: #AAFFAA !important; /* Lighter green on hover */
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
