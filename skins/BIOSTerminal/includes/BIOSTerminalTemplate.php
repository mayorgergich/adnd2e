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
        <?php echo isset($this->data['sitenotice']) ? $this->data['sitenotice'] : ''; ?>
    </div>
    <div class="dos-header-right">
        <?php $this->renderNavigation(['SEARCH']); ?>
    </div>
</div>

<!-- Left sidebar navigation -->
<div id="mw-sidebar" class="dos-sidebar">
    <div class="dos-menu">
        <div class="dos-menu-title">MAIN MENU</div>
        <?php $this->renderNavigation(['SIDEBARNAV']); ?>
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
        <div id="p-personal" class="dos-user-menu">
            <?php $this->renderNavigation(['PERSONAL']); ?>
        </div>
    </div>
</div>

<?php
protected function renderNavigation($elements)
{
    if (in_array('SEARCH', $elements) && isset($this->data['wgScript'])) {
        ?>
        <div id="p-search" role="search">
            <form action="<?php echo htmlspecialchars($this->data['wgScript']); ?>" id="searchform">
                <div>
                    <?php echo $this->makeSearchInput(['id' => 'searchInput']); ?>
                    <?php echo $this->makeSearchButton('go', ['id' => 'searchGoButton', 'class' => 'searchButton']); ?>
                    <input type="hidden" name="title" value="<?php echo htmlspecialchars($this->data['searchtitle'] ?? 'Special:Search'); ?>" />
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
                if (isset($item['links'][0]['html'])) {
                    echo '<li>' . $item['links'][0]['html'] . '</li>';
                } elseif (isset($item['html'])) {
                    echo '<li>' . $item['html'] . '</li>';
                } else {
                    echo '<li>Login</li>';
                }
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
                <h3 id="<?php echo htmlspecialchars($labelId); ?>">
                    <?php echo htmlspecialchars($msgObj->exists() ? $msgObj->text() : $name); ?>
                </h3>
                <ul>
                    <?php
                    foreach ($content as $key => $val) {
                        if (isset($val['link'])) {
                            echo '<li>' . $val['link'] . '</li>';
                        } elseif (isset($val['html'])) {
                            echo '<li>' . $val['html'] . '</li>';
                        } else {
                            echo '<li>' . htmlspecialchars($key) . '</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
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
                echo '<li>' . (isset($item['link']) ? $item['link'] : '') . '</li>';
            }
            ?>
        </ul>
        <?php
    }
}
?>