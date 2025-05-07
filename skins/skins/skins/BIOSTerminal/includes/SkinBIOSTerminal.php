<?php
/**
 * SkinBIOSTerminal class for BIOSTerminal skin
 *
 * @file
 * @ingroup Skins
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
    public function initPage( OutputPage $out ) {
        parent::initPage( $out );
        
        // Default theme (blue/white)
        $out->addModules( 'skins.biosterminal' );
        
        // Check if user has a preference for theme variant
        $user = $this->getUser();
        // Get the user option using MediaWikiServices
        $userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
        $theme = $userOptionsLookup->getOption( $user, 'biosterminal-theme', 'default' );
        
        if ( $theme === 'white-black' ) {
            $out->addModules( 'skins.biosterminal.white-black' );
        } elseif ( $theme === 'black-white' ) {
            $out->addModules( 'skins.biosterminal.black-white' );
        }
        
        // Add terminal cursor effect script
        $out->addInlineScript( 'document.addEventListener("DOMContentLoaded", function() {
            var cursor = document.createElement("span");
            cursor.className = "terminal-cursor";
            if (document.getElementById("firstHeading")) {
                document.getElementById("firstHeading").appendChild(cursor);
            }
        });' );
    }

    /**
     * Ensure we properly set up template data
     */
    public function setupTemplate( $classname ) {
        return new $classname( $this->getConfig() );
    }

    /**
     * Add preferences
     *
     * @param User $user
     * @param array &$preferences
     */
    public static function onGetPreferences( User $user, array &$preferences ) {
        $preferences['biosterminal-theme'] = [
            'type' => 'select',
            'label-message' => 'biosterminal-prefs-theme',
            'section' => 'rendering/skin',
            'options' => [
                wfMessage( 'biosterminal-prefs-theme-default' )->text() => 'default',
                wfMessage( 'biosterminal-prefs-theme-white-black' )->text() => 'white-black',
                wfMessage( 'biosterminal-prefs-theme-black-white' )->text() => 'black-white',
            ],
            'default' => 'default',
        ];
    }
}
