/**
 * BIOSTerminal skin JavaScript
 * Adds terminal-like effects and interactions
 */

// Add error logging for debugging
window.addEventListener('error', function(e) {
  console.error('JS Error:', e.message, 'at', e.filename, 'line', e.lineno);
});

// Check if jQuery is working
document.addEventListener('DOMContentLoaded', function() {
  if (typeof $ === 'undefined' || typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded!');
  } else {
    console.log('jQuery is loaded and working.');
  }
});

// Immediately add loading class to hide content
document.documentElement.className += " js-loading";

// Remove the loading class when everything is ready
window.addEventListener("load", function () {
  // Boot sequence will handle removing the loading class
  // So we don't remove it here automatically
});

// Add no-js class for users without JavaScript
document.documentElement.className = document.documentElement.className.replace(
  "no-js",
  "",
);

(function (mw, $) {
  "use strict";

  // Track if boot sequence has completed
  var bootSequenceComplete = false;

  // Document ready
  $(function () {
    // Add mobile navigation toggle for sidebar
    if ($(window).width() <= 768) {
      // Create toggle button
      var $toggleButton = $('<div class="nav-toggle">MENU ▼</div>');
      $("#mw-sidebar").prepend($toggleButton);

      // Toggle navigation sections on click
      $toggleButton.on("click", function () {
        $("#mw-sidebar .portal:not(:first-child)").toggleClass("active");
        $(this).text(function (i, text) {
          return text === "MENU ▼" ? "MENU ▲" : "MENU ▼";
        });
      });

      // Handle orientation change
      $(window).on("orientationchange", function () {
        setTimeout(function () {
          if ($(window).width() > 768) {
            $("#mw-sidebar .portal").show();
          }
        }, 200);
      });
    }

    // Improved terminal typing effect for headings
    function typeEffect(element, text, i, callback) {
      if (i < text.length) {
        element.innerHTML =
          text.substring(0, i + 1) + '<span class="terminal-cursor"></span>';

        // Random typing speed between 50ms and 150ms for realistic effect
        setTimeout(
          function () {
            typeEffect(element, text, i + 1, callback);
          },
          Math.random() * 100 + 50,
        );
      } else {
        if (callback) {
          // Remove cursor before callback
          element.innerHTML = text;
          setTimeout(callback, 100);
        } else {
          // Keep cursor at the end when typing is complete if no callback
          element.innerHTML = text + '<span class="terminal-cursor"></span>';
        }
      }
    }

    // Function to display a loading percentage
    function displayLoading(element, text, callback) {
      var count = 0;
      var loadingText = text + " (0%)";
      element.innerHTML = loadingText;

      var loadingInterval = setInterval(function () {
        count += Math.floor(Math.random() * 10) + 1; // Random increment for realism
        if (count > 100) {
          count = 100;
          clearInterval(loadingInterval);

          element.innerHTML = text + " (100%).";
          if (callback) {
            setTimeout(callback, 500);
          }
        } else {
          element.innerHTML = text + " (" + count + "%)";
        }
      }, 80); // Update speed
    }

    // Enhanced terminal boot sequence
    function bootSequence() {
      console.log("Starting boot sequence...");
      var overlay = document.createElement("div");
      overlay.style.position = "fixed";
      overlay.style.top = "0";
      overlay.style.left = "0";
      overlay.style.width = "100%";
      overlay.style.height = "100%";
      overlay.style.backgroundColor = "#000080"; // Default blue
      overlay.style.color = "#FFFFFF"; // White text
      overlay.style.fontFamily = "monospace";
      overlay.style.fontSize = "16px";
      overlay.style.zIndex = "9999";
      overlay.style.padding = "2rem";
      overlay.style.boxSizing = "border-box";
      overlay.style.overflow = "auto";

      // Add boot-sequence-active class to make it visible even when js-loading is active
      overlay.className = "boot-sequence-active";

      document.body.appendChild(overlay);

      var bootContainer = document.createElement("div");
      overlay.appendChild(bootContainer);

      // Boot text elements with periods and improved wording
      var bootSequences = [
        {
          text: "BIOS Terminal v1.0",
          isLoading: false,
        },
        {
          text: "Copyright (c) 2025 MediaWiki Custom Skin.",
          isLoading: false,
        },
        {
          text: "",
          isLoading: false,
        },
        {
          text: "Testing system memory",
          isLoading: true,
        },
        {
          text: "Memory test successful.",
          isLoading: false,
        },
        {
          text: "",
          isLoading: false,
        },
        {
          text: "Initializing wiki subsystems",
          isLoading: true,
        },
        {
          text: "Connecting to content database",
          isLoading: true,
        },
        {
          text: "Activating user authentication",
          isLoading: true,
        },
        {
          text: "Starting search system",
          isLoading: true,
        },
        {
          text: "",
          isLoading: false,
        },
        {
          text: "Loading " + (mw.config.get("wgSiteName") || "AD&D 2nd Edition Wiki"),
          isLoading: true,
        },
        {
          text: "",
          isLoading: false,
        },
        {
          text: "READY.",
          isLoading: false,
        },
        {
          text: "",
          isLoading: false,
        },
      ];

      function processBootSequence(sequences, index) {
        if (index >= sequences.length) {
          // Boot sequence complete, fade out overlay
          setTimeout(function () {
            console.log("Boot sequence complete, fading out overlay...");
            overlay.style.transition = "opacity 1s";
            overlay.style.opacity = "0";

            setTimeout(function () {
              document.body.removeChild(overlay);

              // Set bootSequenceComplete flag
              bootSequenceComplete = true;
              
              // Display the main content
              console.log("Displaying main content...");
              showMainContent();
              
              // Apply typing effect to page title
              var heading = document.getElementById("firstHeading");
              if (heading) {
                var originalText = heading.textContent || heading.innerText;
                heading.innerHTML = "";
                typeEffect(heading, originalText, 0);
              }
            }, 1000);
          }, 1000);

          return;
        }

        var sequence = sequences[index];
        var line = document.createElement("div");
        line.style.marginBottom = "0.5rem";
        line.style.minHeight = "1.2em"; // Ensure consistent height
        bootContainer.appendChild(line);

        if (sequence.text === "") {
          // Empty line, move to next
          processBootSequence(sequences, index + 1);
        } else if (sequence.isLoading) {
          // Display loading percentage animation
          displayLoading(line, sequence.text, function () {
            processBootSequence(sequences, index + 1);
          });
        } else {
          // Normal text typing effect
          typeEffect(line, sequence.text, 0, function () {
            processBootSequence(sequences, index + 1);
          });
        }
      }

      // Start boot sequence
      processBootSequence(bootSequences, 0);
    }

    // Function to show main content
    function showMainContent() {
      console.log("Showing main content...");
      
      // Remove the loading class
      document.documentElement.classList.remove("js-loading");
      document.body.classList.add("js-loaded");
      
      // Show the wrapper
      var dosWrapper = document.querySelector(".dos-wrapper");
      if (dosWrapper) {
        dosWrapper.style.display = "flex";
        console.log("Set dos-wrapper to display: flex");
      } else {
        console.error("Could not find .dos-wrapper element");
      }
    }

    // Check if user has already seen boot sequence in this session
    if (!sessionStorage.getItem("biosterminal-boot-shown")) {
      console.log("Starting boot sequence (first visit)");
      bootSequence();
      sessionStorage.setItem("biosterminal-boot-shown", "true");
    } else {
      console.log("Skipping boot sequence (returning visitor)");
      bootSequenceComplete = true;
      // Just show the content immediately
      showMainContent();
      
      // Apply typing effect to page title
      var heading = document.getElementById("firstHeading");
      if (heading) {
        var originalText = heading.textContent || heading.innerText;
        heading.innerHTML = "";
        typeEffect(heading, originalText, 0);
      }
    }

    // Keyboard navigation enhancements
    $(document).on("keydown", function (e) {
      // Alt+H to toggle help dialog
      if (e.altKey && e.keyCode === 72) {
        e.preventDefault();

        // Create help dialog if it doesn't exist
        if (!document.getElementById("terminal-help")) {
          var helpDialog = document.createElement("div");
          helpDialog.id = "terminal-help";
          helpDialog.style.position = "fixed";
          helpDialog.style.top = "50%";
          helpDialog.style.left = "50%";
          helpDialog.style.transform = "translate(-50%, -50%)";
          helpDialog.style.backgroundColor = "var(--background-color, #000080)";
          helpDialog.style.color = "var(--text-color, #FFFFFF)";
          helpDialog.style.border = "1px solid var(--border-color, #C0C0C0)";
          helpDialog.style.padding = "1rem";
          helpDialog.style.zIndex = "1000";
          helpDialog.style.width = "80%";
          helpDialog.style.maxWidth = "600px";
          helpDialog.style.maxHeight = "80vh";
          helpDialog.style.overflow = "auto";

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
            "<p>Press any key to close this dialog.</p>";

          document.body.appendChild(helpDialog);

          // Close dialog on any key press
          var closeHandler = function (e) {
            document.body.removeChild(helpDialog);
            document.removeEventListener("keydown", closeHandler);
          };

          document.addEventListener("keydown", closeHandler);
        } else {
          // If help dialog exists, remove it
          var helpDialog = document.getElementById("terminal-help");
          document.body.removeChild(helpDialog);
        }
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

        // Get current root variables
        var rootStyle = getComputedStyle(document.documentElement);
        var currentBg = rootStyle.getPropertyValue("--background-color").trim();

        // Toggle between theme variants
        if (currentBg === "#0000FF" || currentBg === "#000080") {
          // Blue -> White
          document.documentElement.style.setProperty(
            "--background-color",
            "#FFFFFF",
          );
          document.documentElement.style.setProperty("--text-color", "#000000");
          document.documentElement.style.setProperty("--link-color", "#0000AA");
          document.documentElement.style.setProperty(
            "--link-hover-color",
            "#000088",
          );
          document.documentElement.style.setProperty(
            "--border-color",
            "#000000",
          );
        } else if (currentBg === "#FFFFFF") {
          // White -> Black
          document.documentElement.style.setProperty(
            "--background-color",
            "#000000",
          );
          document.documentElement.style.setProperty("--text-color", "#FFFFFF");
          document.documentElement.style.setProperty("--link-color", "#00AAFF");
          document.documentElement.style.setProperty(
            "--link-hover-color",
            "#AAAAFF",
          );
          document.documentElement.style.setProperty(
            "--border-color",
            "#FFFFFF",
          );
        } else {
          // Black -> Blue
          document.documentElement.style.setProperty(
            "--background-color",
            "#000080",
          );
          document.documentElement.style.setProperty("--text-color", "#FFFFFF");
          document.documentElement.style.setProperty("--link-color", "#FFFFFF");
          document.documentElement.style.setProperty(
            "--link-hover-color",
            "#AAAAAA",
          );
          document.documentElement.style.setProperty(
            "--border-color",
            "#FFFFFF",
          );
        }

        // Show theme change notification
        var notification = document.createElement("div");
        notification.style.position = "fixed";
        notification.style.bottom = "20px";
        notification.style.right = "20px";
        notification.style.backgroundColor = "var(--background-color, #000080)";
        notification.style.color = "var(--text-color, #FFFFFF)";
        notification.style.border = "1px solid var(--border-color, #C0C0C0)";
        notification.style.padding = "10px";
        notification.style.zIndex = "1000";
        notification.textContent = "Theme variant changed.";

        document.body.appendChild(notification);

        setTimeout(function () {
          document.body.removeChild(notification);
        }, 2000);
      }
    });

    // Add terminal prompt to search box
    var searchInput = document.getElementById("searchInput");
    if (searchInput) {
      searchInput.placeholder = "> search...";
    }

    // Add typing effects to edit areas
    $("textarea")
      .on("focus", function () {
        $(this).addClass("terminal-cursor");
      })
      .on("blur", function () {
        $(this).removeClass("terminal-cursor");
      });
  });
  
  // Set a safety timeout to show content if boot sequence fails
  setTimeout(function() {
    if (!bootSequenceComplete) {
      console.log("Safety timeout triggered - showing content");
      document.documentElement.classList.remove("js-loading");
      document.body.classList.add("js-loaded");
      
      var dosWrapper = document.querySelector(".dos-wrapper");
      if (dosWrapper) {
        dosWrapper.style.display = "flex";
      }
    }
  }, 6000); // 6 seconds safety timeout
})(mediaWiki, jQuery);
// Add touch event support for mobile
document.addEventListener('DOMContentLoaded', function() {
  // Convert click events to touch events for mobile
  var links = document.getElementsByTagName('a');
  for (var i = 0; i < links.length; i++) {
    links[i].addEventListener('touchend', function(e) {
      if (!this.clicked) {
        e.preventDefault();
        this.clicked = true;
        this.click();
        setTimeout(function() { this.clicked = false; }.bind(this), 100);
      }
    });
  }
});
