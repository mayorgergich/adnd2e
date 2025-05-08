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

        // Start output buffering to catch PHP warnings
        ob_start();
        $this->outputMainContent();
        // Get the buffer content and clean it
        $content = ob_get_clean();
        // Remove PHP warnings from the content
        $content = preg_replace('/<br>\s*<b>Warning<\/b>\s*:.*?<br>/s', '', $content);
        $content = preg_replace('/<br>\s*<b>Notice<\/b>\s*:.*?<br>/s', '', $content);
        echo $content;

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
                        <div class="dos-header-left">
                            <div class="dos-title"><?php echo $sitename; ?></div>
                            <div class="dos-path">C:\ADND2E\<?php
                                                            if (isset($this->data['title'])) {
                                                                echo strip_tags(htmlspecialchars_decode($this->data['title']));
                                                            } else {
                                                                echo 'MAIN';
                                                            }
                                                            ?></div>
                        </div>
                        <div class="dos-header-center">
                            <?php echo $this->data['sitenotice']; ?>
                        </div>
                        <div class="dos-header-right">
                            <?php $this->renderNavigation(['SEARCH']); ?>
                        </div>
                    </div>

                    <!-- Horizontal navigation -->
                    <div id="mw-navigation" class="dos-nav">
                        <?php $this->renderNavigation(['SIDEBARNAV']); ?>
                        <?php $this->renderNavigation(['PERSONAL']); ?>
                    </div>

                    <!-- Main content area -->
                    <div id="content" class="dos-content">
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

                                <?php if (isset($this->data['bodytext'])) { ?>
                                    <div class="mw-body-content"><?php echo $this->data['bodytext']; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
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
            foreach ($elements as $element) {
                switch ($element) {
                    case 'SEARCH':
                        $this->renderSearch();
                        break;
                    case 'PERSONAL':
                        $this->renderPersonalTools();
                        break;
                    case 'SIDEBARNAV':
                        $this->renderSidebarNav();
                        break;
                }
            }
        }

        protected function renderSearch()
        {
    ?>
        <div id="p-search" class="dos-search">
            <form action="<?php echo htmlspecialchars($this->get('wgScript')); ?>" id="searchform">
                <div>
                    <input type="search" name="search" placeholder="Search ADND2e" aria-label="Search ADND2e"
                        title="Search ADND2e [f]" accesskey="f" id="searchInput">
                    <input type="hidden" name="title" value="Special:Search">
                </div>
            </form>
        </div>
        <?php
        }

        protected function renderPersonalTools()
        {
            $personalTools = $this->getPersonalTools();
            // Only show login link
            if (isset($personalTools['login'])) {
        ?>
            <div id="p-personal" class="dos-user-menu">
                <ul>
                    <li><?php
                        if (isset($personalTools['login']['links'][0]['html'])) {
                            echo $personalTools['login']['links'][0]['html'];
                        } elseif (isset($personalTools['login']['html'])) {
                            echo $personalTools['login']['html'];
                        } else {
                            echo 'Login';
                        }
                        ?></li>
                </ul>
            </div>
        <?php
            }
        }

        protected function renderSidebarNav()
        {
            $sidebar = $this->getSidebar();
            if (isset($sidebar['navigation'])) {
        ?>
            <ul class="dos-nav-list">
                <?php
                foreach ($sidebar['navigation']['content'] as $item) {
                    // Check if link key exists before trying to access it
                    echo '<li>' . (isset($item['link']) ? $item['link'] : '') . '</li>';
                }
                ?>
            </ul>
<?php
            }
        }
    }
