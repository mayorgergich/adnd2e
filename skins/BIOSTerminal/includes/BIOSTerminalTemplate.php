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
        // Output doctype and head manually instead of using headelement
        ?><!DOCTYPE html>
<html class="no-js" lang="<?php echo htmlspecialchars( $this->get( 'lang', 'en' ) ) ?>">
<head>
    <meta charset="<?php echo htmlspecialchars( $this->get( 'charset', 'UTF-8' ) ) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars( $this->get( 'pagetitle' ) ) ?></title>
    <?php $this->html( 'headlinks' ); ?>
    <?php echo $this->getIndicators(); ?>
</head>
<body class="<?php echo htmlspecialchars( $this->getSkinClasses() ) ?>">
<?php
        // Rest of your template code
        ?>
        <div id="mw-wrapper">
            <!-- Left sidebar navigation -->
            <div id="mw-sidebar">
                <div id="p-logo">
                    <a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">
                        <?php echo $this->msg( 'sitetitle' )->escaped(); ?>
                    </a>
                </div>
                
                <?php $this->renderNavigation( [ 'SEARCH' ] ); ?>
                <?php $this->renderNavigation( [ 'SIDEBARNAV' ] ); ?>
            </div>
            
            <!-- Main content area -->
            <div id="content">
                <div id="mw-head">
                    <div class="mw-indicators">
                        <?php echo $this->getIndicators(); ?>
                    </div>
                    <div id="p-personal">
                        <?php $this->renderNavigation( [ 'PERSONAL' ] ); ?>
                    </div>
                </div>
                
                <div class="mw-body">
                    <h1 id="firstHeading" class="firstHeading">
                        <?php $this->html( 'title' ) ?>
                    </h1>
                    
                    <div id="bodyContent">
                        <div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
                        <div id="contentSub"><?php $this->html( 'subtitle' ) ?></div>
                        
                        <?php if ( isset($this->data['undelete']) && $this->data['undelete'] ) { ?>
                            <div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
                        <?php } ?>
                        
                        <?php if ( isset($this->data['newtalk']) && $this->data['newtalk'] ) { ?>
                            <div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
                        <?php } ?>
                        
                        <?php $this->html( 'bodycontent' ) ?>
                        
                        <?php if ( isset($this->data['printfooter']) && $this->data['printfooter'] ) { ?>
                            <div class="printfooter"><?php $this->html( 'printfooter' ) ?></div>
                        <?php } ?>
                        
                        <?php if ( isset($this->data['catlinks']) && $this->data['catlinks'] ) { ?>
                            <?php $this->html( 'catlinks' ) ?>
                        <?php } ?>
                        
                        <?php if ( isset($this->data['dataAfterContent']) && $this->data['dataAfterContent'] ) { ?>
                            <?php $this->html( 'dataAfterContent' ) ?>
                        <?php } ?>
                    </div>
                </div>
                
                <div id="footer">
                    <?php $this->renderNavigation( [ 'ACTIONS', 'VIEWS' ] ); ?>
                    <?php $this->html( 'footer' ) ?>
                </div>
            </div>
        </div>
        
        <?php $this->printTrail(); ?>
        </body>
        </html>
        <?php
    }

    /**
     * Render navigation
     *
     * @param array $elements
     */
    protected function renderNavigation( $elements ) {
        $navigation = $this->data['sidebar'];
        
        if ( in_array( 'SEARCH', $elements ) ) {
            ?>
            <div id="p-search" role="search">
                <h3><?php $this->msg( 'search' ) ?></h3>
                <form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
                    <div>
                        <?php echo $this->makeSearchInput( [ 'id' => 'searchInput' ] ); ?>
                        <?php echo $this->makeSearchButton( 'go', [ 'id' => 'searchGoButton', 'class' => 'searchButton' ] ); ?>
                        <input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
                    </div>
                </form>
            </div>
            <?php
        }
        
        if ( in_array( 'PERSONAL', $elements ) ) {
            ?>
            <ul id="p-personal-list">
                <?php
                foreach ( $this->getPersonalTools() as $key => $item ) {
                    echo $this->makeListItem( $key, $item );
                }
                ?>
            </ul>
            <?php
        }
        
        if ( in_array( 'SIDEBARNAV', $elements ) ) {
            foreach ( $navigation as $name => $content ) {
                if ( $content === false ) {
                    continue;
                }
                
                // Exclude some menus that are handled elsewhere
                if ( in_array( $name, [ 'SEARCH', 'TOOLBOX', 'LANGUAGES' ] ) ) {
                    continue;
                }
                
                $msgObj = $this->getMsg( $name );
                $labelId = Sanitizer::escapeIdForAttribute( "p-$name-label" );
                ?>
                <div class="portal" role="navigation" id="<?php echo htmlspecialchars( Sanitizer::escapeIdForAttribute( "p-$name" ) ) ?>"
                    <?php echo Linker::tooltip( 'p-' . $name ) ?>>
                    <h3 id="<?php echo htmlspecialchars( $labelId ) ?>"><?php echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $name ); ?></h3>
                    <ul>
                        <?php
                        foreach ( $content as $key => $val ) {
                            echo $this->makeListItem( $key, $val );
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
        }
        
        if ( in_array( 'ACTIONS', $elements ) ) {
            ?>
            <div id="p-actions" role="navigation">
                <h3><?php $this->msg( 'actions' ) ?></h3>
                <ul>
                    <?php
                    foreach ( $this->data['content_actions'] as $key => $action ) {
                        echo $this->makeListItem( $key, $action );
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        
        if ( in_array( 'VIEWS', $elements ) ) {
            ?>
            <div id="p-views" role="navigation">
                <h3><?php $this->msg( 'views' ) ?></h3>
                <ul>
                    <?php
                    foreach ( $this->data['view_urls'] as $key => $view ) {
                        echo $this->makeListItem( $key, $view );
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
    }
}
