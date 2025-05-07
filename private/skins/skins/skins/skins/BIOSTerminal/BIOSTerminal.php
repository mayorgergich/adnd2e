<?php
/**
 * BIOSTerminal - A MediaWiki skin that mimics classic BIOS/DOS terminals
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
    die('This is a MediaWiki extension, and must be run from within MediaWiki.');
}

$wgExtensionCredits['skin'][] = [
    'path' => __FILE__,
    'name' => 'BIOSTerminal',
    'namemsg' => 'biosterminal-skin-name',
    'version' => '1.0.0',
    'descriptionmsg' => 'biosterminal-skin-desc',
    'license-name' => 'GPL-2.0-or-later',
    'author' => [
        'Your Name',
    ],
];

// Register skin
$wgValidSkinNames['biosterminal'] = 'BIOSTerminal';

// Register i18n files
$wgMessagesDirs['BIOSTerminal'] = __DIR__ . '/i18n';

// Register modules
$wgResourceModules['skins.biosterminal'] = [
    'localBasePath' => __DIR__,
    'remoteSkinPath' => 'BIOSTerminal',
    'styles' => [
        'BIOSTerminal.css',
    ],
    'scripts' => [
        'resources/js/biosterminal.js',
    ],
    'dependencies' => [
        'mediawiki.util',
    ],
    'targets' => [
        'desktop',
        'mobile',
    ],
];

// Theme variants
$wgResourceModules['skins.biosterminal.white-black'] = [
    'localBasePath' => __DIR__,
    'remoteSkinPath' => 'BIOSTerminal',
    'styles' => [
        'BIOSTerminal-white-black.css',
    ],
    'dependencies' => [
        'skins.biosterminal',
    ],
    'targets' => [
        'desktop',
        'mobile',
    ],
];

$wgResourceModules['skins.biosterminal.black-white'] = [
    'localBasePath' => __DIR__,
    'remoteSkinPath' => 'BIOSTerminal',
    'styles' => [
        'BIOSTerminal-black-white.css',
    ],
    'dependencies' => [
        'skins.biosterminal',
    ],
    'targets' => [
        'desktop',
        'mobile',
    ],
];

// Register autoload classes
$wgAutoloadClasses['SkinBIOSTerminal'] = __DIR__ . '/includes/SkinBIOSTerminal.php';
$wgAutoloadClasses['BIOSTerminalTemplate'] = __DIR__ . '/includes/BIOSTerminalTemplate.php';