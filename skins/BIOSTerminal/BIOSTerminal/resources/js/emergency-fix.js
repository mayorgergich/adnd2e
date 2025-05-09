// Emxyxxergency fix for BIOSTerminal skin rendering issues
// This script executes immediately to ensure content is visible

(function() {
    console.log("Emergency fix running...");
    
    // CRITICAL FIX 1: Remove loading classes immediately
    document.documentElement.classList.remove('js-loading');
    document.documentElement.classList.add('js-loaded');
    
    // CRITICAL FIX 2: Force content visibility
    const emergencyStyle = document.createElement('style');
    emergencyStyle.textContent = `
        /* Force visibility of all content */
        html, body, .dos-wrapper, #mw-wrapper, #content, .mw-body {
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
        }
        
        /* Force DOS wrapper to be flex */
        .dos-wrapper, #mw-wrapper {
            display: flex !important;
        }
        
        /* Remove boot sequence if it's causing issues */
        .boot-sequence {
            display: none !important;
        }
        
        /* Fix content styling for consistent appearance */
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
        div.mw-content-ltr table,
        .mw-parser-output div.panel,
        .mw-parser-output table.wikitable,
        #bodyContent table,
        #bodyContent div.panel,
        pre, code,
        th, td {
            background-color: #000080 !important; /* Classic blue background */
            color: #FFFFFF !important; /* White text */
            border-color: #C0C0C0 !important; /* Light gray borders */
        }
        
        /* Ensure links are visible and clickable */
        a, a:visited {
            color: #00FF00 !important; /* Bright green for links */
            cursor: pointer !important;
            pointer-events: auto !important;
        }
        
        a:hover {
            color: #AAFFAA !important; /* Lighter green on hover */
            text-decoration: underline !important;
        }
        
        /* Ensure form elements remain visible and usable */
        input, select, textarea, button {
            background-color: #000060 !important; /* Slightly darker blue */
            color: #FFFFFF !important;
            border: 1px solid #FFFFFF !important;
            pointer-events: auto !important;
            cursor: pointer !important;
        }
        
        /* Fix for edit sections and similar clickable elements */
        .mw-editsection, .mw-editsection-like {
            pointer-events: auto !important;
        }
    `;
    document.head.appendChild(emergencyStyle);
    
    // CRITICAL FIX 3: Force all elements to be usable
    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM loaded - applying remaining fixes");
        
        // Make sure all links are clickable
        document.querySelectorAll('a').forEach(function(link) {
            link.style.pointerEvents = 'auto';
            link.style.cursor = 'pointer';
        });
        
        // Ensure DOS interface is displayed correctly
        const dosWrapper = document.querySelector('.dos-wrapper');
        if (dosWrapper) {
            dosWrapper.style.display = 'flex';
        }
        
        // Remove any remaining boot sequence elements
        const bootSequence = document.querySelector('.boot-sequence');
        if (bootSequence && bootSequence.parentNode) {
            bootSequence.parentNode.removeChild(bootSequence);
        }
        
        // Add a terminal cursor to headings if missing
        const heading = document.getElementById('firstHeading');
        if (heading && !heading.querySelector('.terminal-cursor')) {
            const cursor = document.createElement('span');
            cursor.className = 'terminal-cursor';
            cursor.style.display = 'inline-block';
            cursor.style.width = '0.5em';
            cursor.style.height = '1em';
            cursor.style.backgroundColor = '#00FF00';
            cursor.style.animation = 'blink 1s step-end infinite';
            cursor.style.marginLeft = '0.25em';
            cursor.style.verticalAlign = 'middle';
            heading.appendChild(cursor);
        }
        
        console.log("Emergency fixes applied successfully");
    });
})();
