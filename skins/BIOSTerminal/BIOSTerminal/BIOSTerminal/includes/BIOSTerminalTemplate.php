<?php

/**
 * BIOSTerminalTemplate class for BIOSTerminal skin
 *
 * @file
 * @ingroup Skins
 */

class BIOSTerminalTemplate extends BaseTemplate
{
    /**
     * Outputs the entire contents of the page
     */
    public function execute()
    {
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
    protected function outputDoctype()
    {
        echo "<!DOCTYPE html>\n";
    }

    /**
     * Output the HTML head
     */
    protected function outputHTML_Head()
    {
        $config = $this->getSkin()->getConfig();
        $sitename = htmlspecialchars($config->get('Sitename'));
        $pagetitle = isset($this->data['pagetitle']) ? htmlspecialchars($this->data['pagetitle']) : $sitename;

?>
        <html class="no-js" lang="<?php echo htmlspecialchars($this->get('lang', 'en')); ?>">

        <head>
            <meta charset="<?php echo htmlspecialchars($this->get('charset', 'UTF-8')); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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

            <!-- Emergency Fix Script -->
            <script src="/skins/BIOSTerminal/resources/js/emergency-fix.js"></script>
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
    protected function outputBodyStart()
    {
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
            <!-- DOS-style boot sequence -->
            <div class="boot-sequence">
                <div class="boot-text">AD&D 2nd Edition Wiki v1.0</div>
                <div class="boot-text">Loading system resources...</div>
                <div class="boot-text">Initializing database connection...</div>
                <div class="boot-text">Loading content modules...</div>
                <div class="boot-text">System ready.</div>
            </div>
            <!-- Main content wrapper -->
            <div class="dos-wrapper">
            <?php
        }

        /**
         * Output the main content
         */
        protected function outputMainContent()
        {
            $config = $this->getSkin()->getConfig();
            $sitename = htmlspecialchars($config->get('Sitename'));

            ?>
                <div id="mw-wrapper" class="dos-interface">
                    <!-- DOS-style header -->
                    <div class="dos-header">
                        <div class="dos-title-bar">
                            <div class="dos-brand">AD&D2e</div>
                            <div class="dos-page-title"><?php echo isset($this->data['title']) ? htmlspecialchars($this->data['title']) : 'MAIN'; ?></div>
                            <div id="p-personal" class="dos-user-menu">
                                <?php $this->renderNavigation(['PERSONAL']); ?>
                            </div>
                        </div>
                        <div class="dos-nav-bar">
                            <?php $this->renderHorizontalNav(); ?>
                        </div>
                    </div>

                    <!-- Main content area -->
                    <div id="content" class="dos-content">
                        <div id="mw-head" class="dos-toolbar">
                            <div class="mw-indicators">
                                <?php
                                if (isset($this->data['indicators']) && is_array($this->data['indicators'])) {
                                    foreach ($this->data['indicators'] as $id => $content) {
                                        echo '<div id="' . htmlspecialchars($id) . '" class="mw-indicator dos-indicator">' . $content . '</div>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="dos-search">
                                <?php $this->renderNavigation(['SEARCH']); ?>
                            </div>
                        </div>

                        <div class="mw-body dos-body">
                            <h1 id="firstHeading" class="firstHeading dos-heading">
                                <?php
                                if (isset($this->data['title'])) {
                                    echo $this->data['title'];
                                } else {
                                    echo 'Page Title';
                                }
                                ?>
                            </h1>

                            <div id="bodyContent" class="dos-content-area">
                                <div id="siteSub" class="dos-subtitle"><?php echo wfMessage('tagline')->escaped(); ?></div>

                                <?php if (isset($this->data['subtitle'])) { ?>
                                    <div id="contentSub" class="dos-subtitle"><?php echo $this->data['subtitle']; ?></div>
                                <?php } ?>

                                <?php if (isset($this->data['undelete']) && $this->data['undelete']) { ?>
                                    <div id="contentSub2" class="dos-undelete"><?php echo $this->data['undelete']; ?></div>
                                <?php } ?>

                                <?php if (isset($this->data['newtalk']) && $this->data['newtalk']) { ?>
                                    <div class="usermessage dos-message"><?php echo $this->data['newtalk']; ?></div>
                                <?php } ?>

                                <?php
                                if (isset($this->data['bodycontent'])) {
                                    echo '<div class="dos-main-content">' . $this->data['bodycontent'] . '</div>';
                                } else {
                                    echo '<div id="mw-content-text" class="dos-error">Content not available</div>';
                                }
                                ?>

                                <?php if (isset($this->data['printfooter']) && $this->data['printfooter']) { ?>
                                    <div class="printfooter dos-footer"><?php echo $this->data['printfooter']; ?></div>
                                <?php } ?>

                                <?php if (isset($this->data['catlinks']) && $this->data['catlinks']) { ?>
                                    <div class="dos-categories"><?php echo $this->data['catlinks']; ?></div>
                                <?php } ?>

                                <?php if (isset($this->data['dataAfterContent']) && $this->data['dataAfterContent']) { ?>
                                    <div class="dos-after-content"><?php echo $this->data['dataAfterContent']; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }

        /**
         * Render horizontal navigation
         */
        protected function renderHorizontalNav()
        {
            // Check if sidebar exists
            if (isset($this->data['sidebar']) && is_array($this->data['sidebar'])) {
                $navigation = $this->data['sidebar'];

                echo '<div class="dos-nav-buttons">';
                
                foreach ($navigation as $name => $content) {
                    if ($content === false) {
                        continue;
                    }

                    // Skip Help and TOOLBOX sections
                    if (in_array($name, ['SEARCH', 'TOOLBOX', 'LANGUAGES', 'HELP'])) {
                        continue;
                    }

                    $msgObj = wfMessage($name);
                    $navLabel = htmlspecialchars($msgObj->exists() ? $msgObj->text() : $name);
                    
                    // Print out main navigation buttons
                    echo '<div class="dos-nav-item">';
                    echo '<button class="dos-nav-button" data-nav-name="' . htmlspecialchars($name) . '">' . $navLabel . '</button>';
                    
                    // Print out submenu
                    if (count($content) > 0) {
                        echo '<div class="dos-nav-submenu" id="submenu-' . htmlspecialchars($name) . '">';
                        echo '<ul>';
                        foreach ($content as $key => $val) {
                            echo $this->makeListItem($key, $val);
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
                
                echo '</div>';
            }
        }

        /**
         * Output the footer scripts
         */
        protected function outputFooter()
        {
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
        protected function outputBodyEnd()
        {
            ?>
            </div>
        </body>

        </html>
        <?php
        }

        /**
         * Render navigation
         *
         * @param array $elements
         */
        protected function renderNavigation($elements)
        {
            if (in_array('SEARCH', $elements) && isset($this->data['wgScript'])) {
        ?>
            <div id="p-search" role="search">
                <form action="<?php echo htmlspecialchars($this->data['wgScript']); ?>" id="searchform">
                    <div>
                        <?php echo $this->makeSearchInput(['id' => 'searchInput', 'placeholder' => '> search...']); ?>
                        <?php echo $this->makeSearchButton('go', ['id' => 'searchGoButton', 'class' => 'searchButton']); ?>
                        <input type="hidden" name="title" value="<?php
                                                                    echo htmlspecialchars($this->data['searchtitle'] ?? 'Special:Search');
                                                                    ?>" />
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
                    if (in_array($name, ['SEARCH', 'TOOLBOX', 'LANGUAGES', 'HELP'])) {
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
