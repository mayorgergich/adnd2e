<?php
/**
 * BIOSTerminal template - Enhanced to mimic retro computer terminal
 */
class BIOSTerminalTemplate extends BaseTemplate {
    public function execute() {
        // Get the site name
        $sitename = $this->getSkin()->getConfig()->get('Sitename');
        
        // Start by outputting the doctype, opening html, head tags
        echo '<!DOCTYPE html>';
        echo '<html class="client-nojs" lang="' . htmlspecialchars($this->get('lang')) . '" dir="' . htmlspecialchars($this->get('dir')) . '">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>' . htmlspecialchars($this->get('title')) . '</title>';
        
        // Output head elements manually
        echo $this->get('headlinks', '');
        echo $this->get('csslinks', '');
        
        // Directly include script as a backup method
        echo '<script src="/skins/BIOSTerminal/resources/js/biosterminal.js"></script>';
        
        echo '</head>';
        echo '<body class="' . htmlspecialchars($this->get('bodyclass', '')) . '">';
?>
<div class="terminal">
    <!-- Terminal header -->
    <div class="term-header">
        <div class="term-title"><?php echo $sitename; ?>::<span class="term-path"><?php echo htmlspecialchars($this->get('title')); ?></span></div>
        <div class="term-search-container">
            <!-- Search box -->
            <form action="<?php echo htmlspecialchars($this->data['wgScript']); ?>" id="searchform">
                <input class="term-search" type="search" name="search" id="searchInput" placeholder="> search...">
                <input type="hidden" name="title" value="Special:Search">
                <input class="term-search-btn" type="submit" value="Execute">
            </form>
        </div>
    </div>
    
    <!-- User menu -->
    <div class="term-user-menu">
        <ul>
            <?php
            foreach ($this->getPersonalTools() as $key => $item) {
                if (isset($item['links'][0]['href'])) {
                    echo '<li><a href="' . 
                         htmlspecialchars($item['links'][0]['href']) . '">' . 
                         htmlspecialchars($item['links'][0]['text'] ?? $key) . '</a></li>';
                } else if (isset($item['href'])) {
                    echo '<li><a href="' . 
                         htmlspecialchars($item['href']) . '">' . 
                         htmlspecialchars($item['text'] ?? $key) . '</a></li>';
                }
            }
            ?>
        </ul>
    </div>
    
    <!-- Main content layout -->
    <div class="term-layout">
        <!-- Sidebar with main navigation -->
        <div class="term-sidebar">
            <div class="term-main-menu">
                <div class="term-main-menu-title">MAIN MENU</div>
                <ul>
                    <?php
                    // Main navigation items
                    if (isset($this->data['sidebar']['navigation'])) {
                        foreach ($this->data['sidebar']['navigation'] as $key => $item) {
                            if (isset($item['href']) && isset($item['text'])) {
                                echo '<li><a href="' . 
                                    htmlspecialchars($item['href']) . '">' . 
                                    htmlspecialchars($item['text']) . '</a></li>';
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        
        <!-- Main content area -->
        <div class="term-content">
            <!-- Page title -->
            <div class="term-page-title">
                <?php echo htmlspecialchars($this->get('title')); ?>
            </div>
            
            <?php if (isset($this->data['subtitle'])): ?>
                <div class="term-subtitle">
                    <?php echo $this->get('subtitle', ''); ?>
                </div>
            <?php endif; ?>
            
            <!-- Main wiki content -->
            <div>
                <?php echo $this->get('bodytext', ''); ?>
            </div>
            
            <!-- Quick links section -->
            <div class="term-links">
                <div class="term-links-title">SYSTEM DIRECTORIES:</div>
                <ul>
                    <?php
                    // Navigation items from sidebar
                    if (isset($this->data['sidebar']) && is_array($this->data['sidebar'])) {
                        foreach ($this->data['sidebar'] as $boxName => $box) {
                            if (is_array($box)) {
                                // Skip certain boxes handled elsewhere
                                if (in_array($boxName, ['SEARCH', 'TOOLBOX', 'LANGUAGES', 'navigation'])) {
                                    continue;
                                }
                                
                                foreach ($box as $key => $item) {
                                    if (is_array($item) && isset($item['href']) && isset($item['text'])) {
                                        echo '<li><a href="' . 
                                            htmlspecialchars($item['href']) . '">' . 
                                            htmlspecialchars($item['text']) . '</a></li>';
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
            
            <!-- Categories -->
            <div id="catlinks">
                <?php echo $this->get('catlinks', ''); ?>
            </div>
            
            <!-- Data after content -->
            <?php echo $this->get('dataAfterContent', ''); ?>
        </div>
    </div>
    
    <!-- Terminal prompt at bottom -->
    <div class="term-prompt">
        C:\<?php echo $sitename; ?>>
    </div>
</div>

<!-- Include inline JavaScript as a backup method -->
<script>
/**
 * BIOSTerminal - Terminal effects for MediaWiki (Inline version)
 */
(function() {
    'use strict';
    
    // Initialize the terminal when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('BIOSTerminal inline script loaded');
        
        // Check if loading animation has been shown before
        if (!sessionStorage.getItem('terminal-loaded')) {
            showLoadingAnimation();
            sessionStorage.setItem('terminal-loaded', 'true');
        }
        
        // Add blinking cursor effect
        addBlinkingCursor();
        
        // Add keyboard shortcuts
        addKeyboardShortcuts();
        
        // Focus search on startup on homepage
        if (document.title.indexOf('Main Page') >= 0) {
            setTimeout(function() {
                const searchInput = document.querySelector('.term-search');
                if (searchInput) {
                    searchInput.focus();
                }
            }, 500);
        }
    });
    
    /**
     * Show loading animation
     */
    function showLoadingAnimation() {
        // Create loading overlay
        const loading = document.createElement('div');
        loading.className = 'term-loading';
        document.body.appendChild(loading);
        
        // Loading text
        const loadingLines = [
            "BIOS Terminal v1.0",
            "Starting system",
            "Memory check: OK",
            "Loading MediaWiki database",
            "Loading AD&D 2nd Edition data",
            "System ready",
            "> Type your search query..."
        ];
        
        // Display loading lines with typing effect
        let delay = 0;
        loadingLines.forEach(function(line, index) {
            setTimeout(function() {
                const lineElement = document.createElement('div');
                lineElement.className = 'term-loading-line';
                lineElement.textContent = line;
                lineElement.style.animationDelay = (index * 0.1) + 's';
                loading.appendChild(lineElement);
                
                // If last line, remove loading screen after delay
                if (index === loadingLines.length - 1) {
                    setTimeout(function() {
                        loading.style.transition = 'opacity 0.5s';
                        loading.style.opacity = 0;
                        setTimeout(function() {
                            loading.remove();
                            
                            // Focus search box after loading
                            const searchInput = document.querySelector('.term-search');
                            if (searchInput) {
                                searchInput.focus();
                            }
                        }, 500);
                    }, 1500);
                }
            }, delay);
            delay += 800; // Delay between lines
        });
    }
    
    /**
     * Add blinking cursor to command prompt
     */
    function addBlinkingCursor() {
        const prompt = document.querySelector('.term-prompt');
        if (prompt) {
            const cursor = document.createElement('span');
            cursor.className = 'term-cursor';
            prompt.appendChild(cursor);
        }
    }
    
    /**
     * Add keyboard shortcuts
     */
    function addKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Alt+S or just / key: Focus search box
            if ((e.altKey && e.key === 's') || (!e.ctrlKey && !e.altKey && !e.metaKey && e.key === '/')) {
                // Don't focus if already in a text input
                if (document.activeElement.tagName !== 'INPUT' && 
                    document.activeElement.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    const searchInput = document.querySelector('.term-search');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }
            }
            
            // Alt+M: Go to main page
            if (e.altKey && e.key === 'm') {
                e.preventDefault();
                document.location.href = '/wiki/Main_Page';
            }
            
            // Alt+H: Toggle terminal help
            if (e.altKey && e.key === 'h') {
                e.preventDefault();
                showTerminalHelp();
            }
            
            // Enter on search box: Submit form
            if (e.key === 'Enter' && document.activeElement.classList.contains('term-search')) {
                e.preventDefault();
                document.getElementById('searchform').submit();
            }
        });
    }
    
    /**
     * Show terminal help
     */
    function showTerminalHelp() {
        // Check if help already exists
        if (document.getElementById('terminal-help')) {
            document.getElementById('terminal-help').remove();
            return;
        }
        
        // Create help dialog
        const help = document.createElement('div');
        help.id = 'terminal-help';
        help.style.position = 'fixed';
        help.style.top = '50%';
        help.style.left = '50%';
        help.style.transform = 'translate(-50%, -50%)';
        help.style.backgroundColor = 'var(--terminal-bg)';
        help.style.color = 'var(--terminal-text)';
        help.style.border = '1px solid var(--terminal-border)';
        help.style.padding = '20px';
        help.style.zIndex = '10000';
        help.style.maxWidth = '80%';
        
        help.innerHTML = `
            <h2>Terminal Help</h2>
            <p>Welcome to the BIOSTerminal interface for AD&D 2nd Edition Wiki.</p>
            <h3>Keyboard Shortcuts:</h3>
            <ul>
                <li><strong>/</strong> or <strong>Alt+S</strong>: Focus search box</li>
                <li><strong>Alt+M</strong>: Go to main page</li>
                <li><strong>Alt+H</strong>: Toggle this help dialog</li>
                <li><strong>Enter</strong>: Submit search</li>
            </ul>
            <p>Press any key to close this dialog.</p>
        `;
        
        document.body.appendChild(help);
        
        // Close on any keypress
        document.addEventListener('keydown', function closeHelp(e) {
            help.remove();
            document.removeEventListener('keydown', closeHelp);
        });
    }
})();
</script>

<?php
        // Output the HTML foot element
        $this->html('bottomscripts');
        $this->html('reporttime');
        echo '</body></html>';
    }
}
