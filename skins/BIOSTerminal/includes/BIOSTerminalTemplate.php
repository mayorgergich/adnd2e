<?php
class BIOSTerminalTemplate extends BaseTemplate {
    public function execute() {
        $sitename = $this->get('sitename');
        ?>

        <div class="dos-header">
            <div class="dos-header-left">
                <div class="dos-title"><?php echo $sitename; ?></div>
                <div class="dos-path">
                    C:\ADND2E\<?php 
                        if (isset($this->data['title'])) {
                            echo strip_tags(htmlspecialchars_decode($this->data['title']));
                        } else {
                            echo 'MAIN';
                        }
                    ?>
                </div>
            </div>
            <div class="dos-header-center">
                <?php echo $this->data['sitenotice'] ?? ''; ?>
            </div>
            <div class="dos-header-right">
                <?php $this->getSkin()->renderNavigation(['SEARCH']); ?>
            </div>
        </div>

        <!-- Left sidebar navigation -->
        <div id="mw-sidebar" class="dos-sidebar">
            <div class="dos-menu">
                <div class="dos-menu-title">MAIN MENU</div>
                <?php $this->getSkin()->renderNavigation(['SIDEBARNAV']); ?>
            </div>
        </div>

        <!-- Main content area -->
        <div id="content" class="dos-content">
            <div id="mw-head" class="dos-toolbar">
                <div class="mw-indicators">
                    <?php
                    if (!empty($this->data['indicators'])) {
                        foreach ($this->data['indicators'] as $id => $content) {
                            echo '<div id="' . htmlspecialchars($id) . '" class="mw-indicator dos-indicator">' . $content . '</div>';
                        }
                    }
                    ?>
                </div>
                <div id="p-personal" class="dos-user-menu">
                    <?php $this->getSkin()->renderNavigation(['PERSONAL']); ?>
                </div>
            </div>

            <?php $this->html('bodycontent'); ?>
        </div>
        <?php
    }
}
