/**
 * BIOSTerminal skin JavaScript - Optimized version
 * Fixes rendering issues while maintaining DOS aesthetic
 */

(function (mw, $) {
  "use strict";

  // IMMEDIATE FIXES - Execute before DOMContentLoaded
  // Remove loading class immediately to prevent blank screen
  document.documentElement.classList.remove("js-loading");
  
  // Make sure content is visible right away
  const style = document.createElement('style');
  style.textContent = `
    html, body {
      visibility: visible !important;
    }
    .dos-wrapper {
      display: flex !important;
    }
    .boot-sequence {
      z-index: 1000;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #000080;
      color: #FFFFFF;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      animation: fadeOut 1.5s ease-in-out 3s forwards;
    }
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; visibility: hidden; }
    }
    /* Fix content styling */
    .mw-body-content, .wikitable, #content table, 
    .mw-parser-output table, th, td {
      background-color: #000080 !important;
      color: #FFFFFF !important;
    }
    /* Ensure all interactive elements work */
    a, button, input, .mw-editsection {
      cursor: pointer !important;
      pointer-events: auto !important;
    }
  `;
  document.head.appendChild(style);

  // Document ready function with simplified boot sequence
  $(function () {
    console.log("BIOSTerminal skin initialized");
    
    // Mark document as loaded
    document.body.classList.add("js-loaded");
    
    // Simplified boot sequence that auto-completes and removes itself
    function simpleBootSequence() {
      // Only show boot sequence once per session
      if (sessionStorage.getItem("biosterminal-boot-shown")) {
        // Make sure boot sequence is removed if already shown
        const existingBoot = document.querySelector(".boot-sequence");
        if (existingBoot && existingBoot.parentNode) {
          existingBoot.parentNode.removeChild(existingBoot);
        }
        return;
      }
      
      // Create simpler boot sequence
      const bootSequence = document.createElement("div");
      bootSequence.className = "boot-sequence";
      
      const messages = [
        "AD&D 2nd Edition Wiki v1.0",
        "Loading system resources...",
        "Initializing database connection...",
        "System ready."
      ];
      
      messages.forEach((text, index) => {
        const line = document.createElement("div");
        line.style.color = "#FFFFFF";
        line.style.margin = "5px 0";
        line.style.opacity = "0";
        line.style.animation = `fadeIn 0.5s ease-in-out ${index * 0.5}s forwards`;
        line.textContent = text;
        bootSequence.appendChild(line);
      });
      
      document.body.appendChild(bootSequence);
      
      // Automatically clean up boot sequence after animation
      setTimeout(() => {
        if (bootSequence.parentNode) {
          bootSequence.parentNode.removeChild(bootSequence);
        }
      }, 5000);
      
      // Mark as shown for this session
      sessionStorage.setItem("biosterminal-boot-shown", "true");
    }
    
    // Run boot sequence
    simpleBootSequence();
    
    // Simplified cursor effect for headings
    const heading = document.getElementById("firstHeading");
    if (heading) {
      const cursor = document.createElement("span");
      cursor.className = "terminal-cursor";
      cursor.style.display = "inline-block";
      cursor.style.width = "0.5em";
      cursor.style.height = "1em";
      cursor.style.backgroundColor = "#00FF00";
      cursor.style.verticalAlign = "middle";
      cursor.style.animation = "blink 1s step-end infinite";
      heading.appendChild(cursor);
    }
    
    // Mobile navigation improvements
    if ($(window).width() <= 768) {
      const $toggleButton = $('<div class="nav-toggle">MENU ▼</div>');
      $toggleButton.css({
        'padding': '10px', 
        'background-color': '#000080',
        'color': '#FFFFFF',
        'cursor': 'pointer',
        'text-align': 'center',
        'border-bottom': '1px solid #FFFFFF'
      });
      
      $("#mw-sidebar").prepend($toggleButton);
      
      $toggleButton.on("click", function () {
        $("#mw-sidebar .portal:not(:first-child)").toggle();
        $(this).text(function (i, text) {
          return text === "MENU ▼" ? "MENU ▲" : "MENU ▼";
        });
      });
      
      // Initially hide the menu items on mobile for space
      $("#mw-sidebar .portal:not(:first-child)").hide();
    }
    
    // Keyboard shortcuts - simplified
    $(document).on("keydown", function (e) {
      // Alt+S to focus search box
      if (e.altKey && e.key === "s") {
        e.preventDefault();
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
          searchInput.focus();
        }
      }
      
      // Alt+M to go to main page
      if (e.altKey && e.key === "m") {
        e.preventDefault();
        window.location.href = mw.util.getUrl(mw.config.get("wgMainPageTitle") || "Main_Page");
      }
      
      // Alt+E to edit current page
      if (e.altKey && e.key === "e") {
        e.preventDefault();
        const editTab = document.querySelector("#ca-edit a");
        if (editTab) {
          window.location.href = editTab.href;
        }
      }
    });
    
    // Add terminal prompt to search box
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
      searchInput.placeholder = "> search...";
    }
    
    // Ensure all links are clickable
    document.querySelectorAll('a').forEach(link => {
      link.style.pointerEvents = "auto";
      link.style.cursor = "pointer";
    });
    
    // Fix any potential content styling issues that may cause rendering problems
    [
      '.dos-content-area', '.mw-body-content', '.wikitable', 
      '#content table', '.mw-parser-output table'
    ].forEach(selector => {
      const elements = document.querySelectorAll(selector);
      elements.forEach(el => {
        el.style.backgroundColor = "#000080";
        el.style.color = "#FFFFFF";
      });
    });
    
    // Add accessibility improvements
    const accessibilityStyle = document.createElement('style');
    accessibilityStyle.textContent = `
      /* Improve contrast for better readability */
      a { color: #00FF00 !important; }
      a:hover { color: #AAFFAA !important; text-decoration: underline !important; }
      
      /* Ensure form elements are visible */
      input, select, textarea {
        background-color: #000060 !important;
        color: #FFFFFF !important;
        border: 1px solid #FFFFFF !important;
      }
      
      /* Make sure tables are readable */
      table, th, td {
        border: 1px solid #AAAAAA !important;
      }
    `;
    document.head.appendChild(accessibilityStyle);
  });

})(mediaWiki, jQuery);
