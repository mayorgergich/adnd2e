/**
 * BIOSTerminal skin JavaScript
 * Adds terminal-like effects and interactions
 */

// Immediately add loading class to hide content
document.documentElement.className += " js-loading";

// Remove the loading class when everything is ready
window.addEventListener("load", function () {
  // Remove the loading class
  document.documentElement.classList.remove("js-loading");
});

// Add no-js class for users without JavaScript
document.documentElement.className = document.documentElement.className.replace(
  "no-js",
  "",
);

(function (mw, $) {
  "use strict";

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
      var overlay = document.createElement("div");
      overlay.style.position = "fixed";
      overlay.style.top = "0";
      overlay.style.left = "0";
      overlay.style.width = "100%";
      overlay.style.height = "100%";
      overlay.style.backgroundColor = "var(--background-color)";
      overlay.style.color = "var(--text-color)";
      overlay.style.fontFamily = "var(--font-family)";
      overlay.style.fontSize = "var(--font-size)";
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
          text: "Loading " + mw.config.get("wgSiteName"),
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
            overlay.style.transition = "opacity 1s";
            overlay.style.opacity = "0";

            setTimeout(function () {
              document.body.removeChild(overlay);

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

    // Check if user has already seen boot sequence in this session
    if (!sessionStorage.getItem("biosterminal-boot-shown")) {
      bootSequence();
      sessionStorage.setItem("biosterminal-boot-shown", "true");
    } else {
      // Just apply typing effect to page title
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
          helpDialog.style.backgroundColor = "var(--background-color)";
          helpDialog.style.color = "var(--text-color)";
          helpDialog.style.border = "1px solid var(--border-color)";
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
        if (currentBg === "#0000FF") {
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
            "#0000FF",
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
        notification.style.backgroundColor = "var(--background-color)";
        notification.style.color = "var(--text-color)";
        notification.style.border = "1px solid var(--border-color)";
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

  // Wait for the DOM to be fully loaded
  document.addEventListener("DOMContentLoaded", function () {
    // Add loading class to body
    document.body.classList.add("js-loading");

    // Boot sequence timing
    setTimeout(function () {
      document.body.classList.remove("js-loading");
      document.body.classList.add("js-loaded");

      // Show the main content
      document.querySelector(".dos-wrapper").style.display = "flex";
    }, 4000);

    // Add terminal cursor effect to headings
    const headings = document.querySelectorAll("h1, h2, h3");
    headings.forEach(function (heading) {
      const cursor = document.createElement("span");
      cursor.className = "terminal-cursor";
      heading.appendChild(cursor);
    });

    // Add typing effect to certain elements
    const typingElements = document.querySelectorAll(".dos-title, .dos-path");
    typingElements.forEach(function (element) {
      const text = element.textContent;
      element.textContent = "";
      let i = 0;

      function typeWriter() {
        if (i < text.length) {
          element.textContent += text.charAt(i);
          i++;
          setTimeout(typeWriter, 50);
        }
      }

      typeWriter();
    });

    // Add hover effects to menu items
    const menuItems = document.querySelectorAll(".dos-menu a");
    menuItems.forEach(function (item) {
      item.addEventListener("mouseover", function () {
        this.style.color = "var(--dos-highlight)";
        this.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
      });

      item.addEventListener("mouseout", function () {
        this.style.color = "var(--dos-text)";
        this.style.backgroundColor = "transparent";
      });
    });

    // Add keyboard navigation
    document.addEventListener("keydown", function (e) {
      // Alt + M to focus on menu
      if (e.altKey && e.key === "m") {
        const firstMenuItem = document.querySelector(".dos-menu a");
        if (firstMenuItem) {
          firstMenuItem.focus();
        }
      }

      // Alt + S to focus on search
      if (e.altKey && e.key === "s") {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
          searchInput.focus();
        }
      }
    });
  });
})(mediaWiki, jQuery);
