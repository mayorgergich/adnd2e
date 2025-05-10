<?php
/**
 * SkinBIOSTerminal - Enhanced retro terminal skin for MediaWiki
 */
use MediaWiki\MediaWikiServices;

class SkinBIOSTerminal extends SkinTemplate {
    /** @var string */
    public $skinname = 'biosterminal';
    /** @var string */
    public $stylename = 'BIOSTerminal';
    /** @var string */
    public $template = 'BIOSTerminalTemplate';

    /**
     * Add CSS via ResourceLoader
     *
     * @param OutputPage $out
     */
    public function initPage(OutputPage $out) {
        parent::initPage($out);
        
        // Add our modules
        $out->addModules(['skins.biosterminal', 'skins.biosterminal.scripts']);
        $out->addModuleStyles(['skins.biosterminal.styles']);
        
        // Force the browser to load the font
        $out->addHeadItem('font-preload', 
            '<link rel="preload" href="/skins/BIOSTerminal/resources/fonts/MorePerfectDOSVGA.ttf" as="font" type="font/ttf" crossorigin>'
        );
        
        // Add inline script to check for loading animation
        $out->addInlineScript('
            document.addEventListener("DOMContentLoaded", function() {
                console.log("BIOSTerminal skin loaded");
                
                // Check if loading animation has been shown before
                if (!sessionStorage.getItem("terminal-loaded")) {
                    console.log("First visit - should show loading animation");
                }
            });
        ');
    }

    /**
     * @param string $classname
     * @return BIOSTerminalTemplate
     */
    public function setupTemplate($classname) {
        return new BIOSTerminalTemplate();
    }
}
