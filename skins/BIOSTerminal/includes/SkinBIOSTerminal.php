<?php
/**
 * SkinBIOSTerminal class for BIOSTerminal skin
 *
 * @file
 * @ingroup Skins
 */

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
		$theme = $user->getOption( 'biosterminal-theme' );
		
		if ( $theme === 'white-black' ) {
			$out->addModules( 'skins.biosterminal.white-black' );
		} elseif ( $theme === 'black-white' ) {
			$out->addModules( 'skins.biosterminal.black-white' );
		}
		
		// Add terminal cursor effect script
		$out->addInlineScript( 'document.addEventListener("DOMContentLoaded", function() {
			var cursor = document.createElement("span");
			cursor.className = "terminal-cursor";
			document.getElementById("firstHeading").appendChild(cursor);
		});' );
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