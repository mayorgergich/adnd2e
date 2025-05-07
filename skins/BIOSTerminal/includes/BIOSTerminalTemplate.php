<?php
/**
 * BIOSTerminalTemplate class for BIOSTerminal skin
 *
 * @file
 * @ingroup Skins
 */

class BIOSTerminalTemplate extends BaseTemplate {
    /**
     * Outputs the entire contents of the page
     */
    public function execute() {
        // Manually output the HTML structure instead of using headelement
        $this->outputDoctype();
        $this->outputHTML_Head();
        $this->outputBodyStart();
        $this->outputMainContent();
        $this->outputFooter();
        $this->outputBodyEnd();
    }
    
    /**
     * Output the doctype
     */
    protected function outputDoctype() {
        echo "<!DOCTYPE html>\n";
    }
    
    /**
     * Output the HTML head
     */
    protected function outputHTML_Head() {
        $config = $this->getSkin()->getConfig();
        $sitename = htmlspecialchars($config->get('Sitename'));
        $pagetitle = isset($this->data['pagetitle']) ? htmlspecialchars($this->data['pagetitle']) : $sitename;
        
        ?>
        <html class="no-js" lang="<?php echo htmlspecialchars($this->get('lang', 'en')); ?>">
        <head>
            <meta charset="<?php echo htmlspecialchars($this->get('charset', 'UTF-8')); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pagetitle; ?></title>
            
            <!-- Base styles -->
            <?php
            // Output stylesheets manually
            $skinname = htmlspecialchars($this->getSkin()->getSkinName());
            echo '<link rel="stylesheet" href="' . htmlspecialchars($config->get('ResourceBasePath')) . '/load.php?only=styles&skin=' . $skinname . '&debug=false">';
            
            // Include any other head items
            if (isset($this->data['csslinks'])) {
                echo $this->data['csslinks'];
            }
            
            // Add scripts
            if (isset($this->data['jslinks'])) {
                echo $this->data['jslinks'];
            }
            ?>
            
            <link rel="shortcut icon" href="<?php echo htmlspecialchars($config->get('ResourceBasePath')); ?>/favicon.ico">
            
            <!-- FOUC Prevention Script -->
            <script>
                // Mark the page as loading until JavaScript is ready
                document.documentElement.className += ' js-loading';
                
                // Add initial styles to prevent FOUC
                (function() {
                    var style = document.createElement('style');
                    style.type = 'text/css';
                    style.innerHTML = `
                        html.js-loading {
                            visibility: hidden;
                        }
                        html.js-loading .boot-sequence-active {
                            visibility: visible;
                        }
                        html.no-js {
                            visibility: visible;
                        }
                    `;
                    document.getElementsByTagName('head')[0].appendChild(style);
                })();
            </script>
            <!-- End FOUC Prevention Script -->
        </head>
        <?php
    }
    
    /**
     * Output the body start
     */
    protected function outputBodyStart() {
        $classes = array();
        
        // Add page-specific classes
        if (isset($this->data['pageclass'])) {
            $classes[] = $this->data['pageclass'];
        }
        
        // Add skin class
        $classes[] = 'skin-' . $this->getSkin()->getSkinName();
        
        // Add other classes
        if (isset($this->data['body_class'])) {
            $classes[] = $this->data['body_class'];
        }
        
        $classString = implode(' ', $classes);
        ?>
        <body class="<?php echo htmlspecialchars($classString); ?>">
        <?php
    }
    
    /**
     * Output the main content
     */
    protected function outputMainContent() {
        $config = $this->getSkin()->getConfig();
        $sitename = htmlspecialchars($config->get('Sitename'));
        
        ?>
        <div id="mw-wrapper">
            <!-- Left sidebar navigation -->
            <div id="mw-sidebar">
                <div id="p-logo">
                    <a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'] ?? '#'); ?>">
                        <?php echo $sitename; ?>
                    </a>
                </div>
                
                <?php $this->renderNavigation(['SEARCH']); ?>
                <?php $this->renderNavigation(['SIDEBARNAV']); ?>
            </div>
            
            <!-- Main content area -->
            <div id="content">
                <div id="mw-head">
                    <div class="mw-indicators">
                        <?php 
                        if (isset($this->data['indicators']) && is_array($this->data['indicators'])) {
                            foreach ($this->data['indicators'] as $id => $content) {
                                echo '<div id="' . htmlspecialchars($id) . '" class="mw-indicator">' . $content . '</div>';
                            }
                        }
                        ?>
                    </div>
                    <div id="p-personal">
                        <?php $this->renderNavigation(['PERSONAL']); ?>
                    </div>
                </div>
                
                <div class="mw-body">
                    <h1 id="firstHeading" class="firstHeading">
                        <?php 
                        if (isset($this->data['title'])) {
                            echo $this->data['title'];
                        } else {
                            echo 'Page Title';
                        }
                        ?>
                    </h1>
                    
                    <div id="bodyContent">
                        <div id="siteSub"><?php echo wfMessage('tagline')->escaped(); ?></div>
                        
                        <?php if (isset($this->data['subtitle'])) { ?>
                            <div id="contentSub"><?php echo $this->data['subtitle']; ?></div>
                        <?php } ?>
                        
                        <?php if (isset($this->data['undelete']) && $this->data['undelete']) { ?>
                            <div id="contentSub2"><?php echo $this->data['undelete']; ?></div>
                        <?php } ?>
                        
                        <?php if (isset($this->data['newtalk']) && $this->data['newtalk']) { ?>
                            <div class="usermessage"><?php echo $this->data['newtalk']; ?></div>
                        <?php } ?>
                        
                        <?php 
                        if (isset($this->data['bodycontent'])) {
                            echo $this->data['bodycontent'];
                        } else {
                            echo '<div id="mw-content-text">Content not available</div>';
                        }
                        ?>
                        
                        <?php if (isset($this->data['printfooter']) && $this->data['printfooter']) { ?>
                            <div class="printfooter"><?php echo $this->data['printfooter']; ?></div>
                        <?php } ?>
                        
                        <?php if (isset($this->data['catlinks']) && $this->data['catlinks']) { ?>
                            <?php echo $this->data['catlinks']; ?>
                        <?php } ?>
                        
                        <?php if (isset($this->data['dataAfterContent']) && $this->data['dataAfterContent']) { ?>
                            <?php echo $this->data['dataAfterContent']; ?>
                        <?php } ?>
                    </div>
                </div>
                
                <div id="footer">
                    <?php $this->renderNavigation(['ACTIONS', 'VIEWS']); ?>
                    <?php if (isset($this->data['footer'])) { echo $this->data['footer']; } ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Output the footer scripts
     */
    protected function outputFooter() {
        // Output any bottom scripts
        if (isset($this->data['bottomscripts'])) {
            echo $this->data['bottomscripts'];
        }
        
        // Include any trailing items - this replaces printTrail()
        if (isset($this->data['trackingcategories'])) {
            echo $this->data['trackingcategories'];
        }
        
        // Other trailing elements
        if (isset($this->data['debughtml'])) {
            echo $this->data['debughtml'];
        }
    }
    
    /**
     * Output the body end
     */
    protected function outputBodyEnd() {
        ?>
        </body>
        </html>
        <?php
    }

    /**
     * Render navigation
     *
     * @param array $elements
     */
    protected function renderNavigation($elements) {
        if (in_array('SEARCH', $elements) && isset($this->data['wgScript'])) {
            ?>
            <div id="p-search" role="search">
                <h3><?php echo wfMessage('search')->escaped(); ?></h3>
                <form action="<?php echo htmlspecialchars($this->data['wgScript']); ?>" id="searchform">
                    <div>
                        <?php echo $this->makeSearchInput(['id' => 'searchInput']); ?>
                        <?php echo $this->makeSearchButton('go', ['id' => 'searchGoButton', 'class' => 'searchButton']); ?>
                        <input type="hidden" name="title" value="<?php 
                            echo htmlspecialchars($this->data['searchtitle'] ?? 'Special:Search'); 
                        ?>"/>
                    </div>
                </form>
            </div>
            <?php
        }
        
        if (in_array('PERSONAL', $elements)) {
            ?>
            <ul id="p-personal-list">
                <?php
                foreach ($this->getPersonalTools() as $key => $item) {
                    echo $this->makeListItem($key, $item);
                }
                ?>
            </ul>
            <?php
        }
        
        if (in_array('SIDEBARNAV', $elements) && isset($this->data['sidebar']) && is_array($this->data['sidebar'])) {
            $navigation = $this->data['sidebar'];
            
            foreach ($navigation as $name => $content) {
                if ($content === false) {
                    continue;
                }
                
                // Exclude some menus that are handled elsewhere
                if (in_array($name, ['SEARCH', 'TOOLBOX', 'LANGUAGES'])) {
                    continue;
                }
                
                $msgObj = wfMessage($name);
                $labelId = Sanitizer::escapeIdForAttribute("p-$name-label");
                ?>
                <div class="portal" role="navigation" id="<?php echo htmlspecialchars(Sanitizer::escapeIdForAttribute("p-$name")); ?>">
                    <h3 id="<?php echo htmlspecialchars($labelId); ?>"><?php 
                        echo htmlspecialchars($msgObj->exists() ? $msgObj->text() : $name); 
                    ?></h3>
                    <ul>
                        <?php
                        foreach ($content as $key => $val) {
                            echo $this->makeListItem($key, $val);
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
        }
        
        if (in_array('ACTIONS', $elements) && isset($this->data['content_actions']) && is_array($this->data['content_actions'])) {
            ?>
            <div id="p-actions" role="navigation">
                <h3><?php echo wfMessage('actions')->escaped(); ?></h3>
                <ul>
                    <?php
                    foreach ($this->data['content_actions'] as $key => $action) {
                        echo $this->makeListItem($key, $action);
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        
        if (in_array('VIEWS', $elements) && isset($this->data['view_urls']) && is_array($this->data['view_urls'])) {
            ?>
            <div id="p-views" role="navigation">
                <h3><?php echo wfMessage('views')->escaped(); ?></h3>
                <ul>
                    <?php
                    foreach ($this->data['view_urls'] as $key => $view) {
                        echo $this->makeListItem($key, $view);
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
    }
}
