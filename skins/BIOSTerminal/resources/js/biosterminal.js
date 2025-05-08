/**
 * BIOSTerminal skin JavaScript
 * Simplified and fixed version
 */

(function (mw, $) {
  "use strict";

  // Immediately remove loading class to ensure content is visible
  document.documentElement.classList.remove("js-loading");
  document.body.classList.add("js-loaded");

  // Document ready function
  $(function () {
    console.log("BIOSTerminal skin initialized");
    
    // Show the wrapper immediately
    var dosWrapper = document.querySelector(".dos-wrapper");
    if (dosWrapper) {
      dosWrapper.style.display = "flex";
      console.log("DOS wrapper displayed");
    }

    // Make terminal typing effect for headings
    function typeEffect(element, text, i, callback) {
      if (i < text.length) {
        element.innerHTML = text.substring(0, i + 1) + '<span class="terminal-cursor"></span>';
        setTimeout(function () {
          typeEffect(element, text, i + 1, callback);
        }, 50);
      } else {
        if (callback) {
          element.innerHTML = text;
          setTimeout(callback, 100);
        } else {
          element.innerHTML = text + '<span class="terminal-cursor"></span>';
        }
      }
    }

    // Apply typing effect to page title
    var heading = document.getElementById("firstHeading");
    if (heading) {
      var originalText = heading.textContent || heading.innerText;
      heading.innerHTML = "";
      typeEffect(heading, originalText, 0);
    }

    // Simplified boot sequence
    function simpleBootSequence() {
      var bootMessages = [
        "BIOS Terminal v1.0",
        "Loading " + (mw.config.get("wgSiteName") || "AD&D 2nd Edition Wiki"),
        "System Ready."
      ];
      
      var bootArea = document.createElement("div");
      bootArea.style.position = "fixed";
      bootArea.style.top = "10px";
      bootArea.style.left = "10px";
      bootArea.style.background = "rgba(0, 0, 128, 0.8)";
      bootArea.style.color = "#FFFFFF";
      bootArea.style.padding = "10px";
      bootArea.style.borderRadius = "5px";
      bootArea.style.zIndex = "1000";
      bootArea.style.fontFamily = "monospace";
      bootArea.style.fontSize = "14px";
      bootArea.style.maxWidth = "400px";
      
      document.body.appendChild(bootArea);
      
      var messageIndex = 0;
      
      function showNextMessage() {
        if (messageIndex < bootMessages.length) {
          var messageElem = document.createElement("div");
          messageElem.textContent = bootMessages[messageIndex];
          bootArea.appendChild(messageElem);
          messageIndex++;
          setTimeout(showNextMessage, 500);
        } else {
          setTimeout(function() {
            bootArea.style.opacity = "0";
            bootArea.style.transition = "opacity 1s";
            setTimeout(function() {
              document.body.removeChild(bootArea);
            }, 1000);
          }, 1000);
        }
      }
      
      showNextMessage();
    }

    // Show boot sequence once per session
    if (!sessionStorage.getItem("biosterminal-boot-shown")) {
      simpleBootSequence();
      sessionStorage.setItem("biosterminal-boot-shown", "true");
    }

    // Add mobile navigation toggle for sidebar
    if ($(window).width() <= 768) {
      var $toggleButton = $('<div class="nav-toggle">MENU ▼</div>');
      $toggleButton.css({
        'padding': '10px', 
        'background-color': '#000080',
        'color': '#FFFFFF',
        'cursor': 'pointer',
        'text-align': 'center',
        'border-bottom': '1px solid #FFFFFF'
      });
      
      $("#mw-sidebar").prepend($toggleButton);

      // Toggle navigation sections on click
      $toggleButton.on("click", function () {
        $("#mw-sidebar .portal:not(:first-child)").toggle();
        $(this).text(function (i, text) {
          return text === "MENU ▼" ? "MENU ▲" : "MENU ▼";
        });
      });

      // Initially hide the menu items on mobile
      $("#mw-sidebar .portal:not(:first-child)").hide();
    }

    // Keyboard navigation
    $(document).on("keydown", function (e) {
      // Alt+H to toggle help dialog
      if (e.altKey && e.keyCode === 72) {
        e.preventDefault();
        showHelpDialog();
      }

      // Alt+S to focus search box
      if (e.altKey && e.keyCode === 83) {
        e.preventDefault();
        var searchInput = document.getElementById("searchInput");
        if (searchInput) {
          searchInput.focus();
        }
      }

      // Alt+M to go to main page
      if (e.altKey && e.keyCode === 77) {
        e.preventDefault();
        window.location.href = mw.util.getUrl(mw.config.get("wgMainPageTitle"));
      }

      // Alt+E to edit current page
      if (e.altKey && e.keyCode === 69) {
        e.preventDefault();
        var editLink = document.getElementById("ca-edit");
        if (editLink && editLink.querySelector("a")) {
          window.location.href = editLink.querySelector("a").href;
        }
      }

      // Alt+T to toggle theme variant
      if (e.altKey && e.keyCode === 84) {
        e.preventDefault();
        toggleTheme();
      }
    });

    // Help dialog function
    function showHelpDialog() {
      // Remove existing dialog if present
      var existingDialog = document.getElementById("terminal-help");
      if (existingDialog) {
        document.body.removeChild(existingDialog);
        return;
      }

      var helpDialog = document.createElement("div");
      helpDialog.id = "terminal-help";
      helpDialog.style.position = "fixed";
      helpDialog.style.top = "50%";
      helpDialog.style.left = "50%";
      helpDialog.style.transform = "translate(-50%, -50%)";
      helpDialog.style.backgroundColor = "var(--dos-bg, #000080)";
      helpDialog.style.color = "var(--dos-text, #FFFFFF)";
      helpDialog.style.border = "1px solid var(--dos-border, #C0C0C0)";
      helpDialog.style.padding = "1rem";
      helpDialog.style.zIndex = "1000";
      helpDialog.style.width = "80%";
      helpDialog.style.maxWidth = "600px";
      helpDialog.style.maxHeight = "80vh";
      helpDialog.style.overflow = "auto";
      helpDialog.style.fontFamily = "monospace";

      helpDialog.innerHTML =
        "<h2>Terminal Help</h2>" +
        "<p>Welcome to the BIOSTerminal skin. Here are some keyboard shortcuts:</p>" +
        "<ul>" +
        "<li><strong>Alt+H</strong>: Toggle this help dialog</li>" +
        "<li><strong>Alt+S</strong>: Focus search box</li>" +
        "<li><strong>Alt+M</strong>: Return to main page</li>" +
        "<li><strong>Alt+E</strong>: Edit current page (if available)</li>" +
        "<li><strong>Alt+T</strong>: Toggle between theme variants</li>" +
        "</ul>" +
        "<p>Press Escape or click anywhere to close this dialog.</p>";

      document.body.appendChild(helpDialog);

      // Close dialog on click anywhere or escape key
      function closeHandler(e) {
        if (e.type === "keydown" && e.keyCode !== 27) {
          return;
        }
        document.body.removeChild(helpDialog);
        document.removeEventListener("keydown", closeHandler);
        document.removeEventListener("click", closeHandler);
      }

      document.addEventListener("keydown", closeHandler);
      document.addEventListener("click", function clickHandler(e) {
        if (e.target !== helpDialog) {
          closeHandler(e);
        }
      });
    }

    // Theme toggle function
    function toggleTheme() {
      // Get current root variables
      var rootStyle = getComputedStyle(document.documentElement);
      var currentBg = rootStyle.getPropertyValue("--dos-bg").trim() || "#000080";

      // Toggle between theme variants
      if (currentBg === "#000080" || currentBg === "#0000FF") {
        // Blue -> White
        document.documentElement.style.setProperty("--dos-bg", "#FFFFFF");
        document.documentElement.style.setProperty("--dos-text", "#000000");
        document.documentElement.style.setProperty("--dos-highlight", "#0000AA");
        document.documentElement.style.setProperty("--dos-border", "#000000");
      } else if (currentBg === "#FFFFFF") {
        // White -> Black
        document.documentElement.style.setProperty("--dos-bg", "#000000");
        document.documentElement.style.setProperty("--dos-text", "#FFFFFF");
        document.documentElement.style.setProperty("--dos-highlight", "#00AAFF");
        document.documentElement.style.setProperty("--dos-border", "#FFFFFF");
      } else {
        // Black -> Blue
        document.documentElement.style.setProperty("--dos-bg", "#000080");
        document.documentElement.style.setProperty("--dos-text", "#FFFFFF");
        document.documentElement.style.setProperty("--dos-highlight", "#00FF00");
        document.documentElement.style.setProperty("--dos-border", "#C0C0C0");
      }

      // Show theme change notification
      showNotification("Theme variant changed");
    }

    // Show notification
    function showNotification(message) {
      var notification = document.createElement("div");
      notification.style.position = "fixed";
      notification.style.bottom = "20px";
      notification.style.right = "20px";
      notification.style.backgroundColor = "var(--dos-bg, #000080)";
      notification.style.color = "var(--dos-text, #FFFFFF)";
      notification.style.border = "1px solid var(--dos-border, #C0C0C0)";
      notification.style.padding = "10px";
      notification.style.zIndex = "9999";
      notification.style.fontFamily = "monospace";
      notification.textContent = message;

      document.body.appendChild(notification);

      setTimeout(function () {
        notification.style.opacity = "0";
        notification.style.transition = "opacity 0.5s";
        setTimeout(function() {
          if (notification.parentNode) {
            document.body.removeChild(notification);
          }
        }, 500);
      }, 2000);
    }

    // Add terminal prompt to search box
    var searchInput = document.getElementById("searchInput");
    if (searchInput) {
      searchInput.placeholder = "> search...";
    }

    // Ensure all links are clickable
    setTimeout(function() {
      var allLinks = document.querySelectorAll('a');
      allLinks.forEach(function(link) {
        link.style.pointerEvents = "auto";
      });
    }, 500);
  });

  // Add touch event support for mobile
  document.addEventListener('DOMContentLoaded', function() {
    // Make sure all interactive elements have pointer-events auto
    var interactiveElements = document.querySelectorAll('a, button, input, select, textarea');
    for (var i = 0; i < interactiveElements.length; i++) {
      interactiveElements[i].style.pointerEvents = "auto";
    }
  });

})(mediaWiki, jQuery);
