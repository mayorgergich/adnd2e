# BIOSTerminal MediaWiki Skin

A custom MediaWiki skin that mimics classic BIOS/DOS terminals of early computing, featuring retro styling with the MorePerfectDOSVGA font and multiple color schemes.

## Features

- Classic BIOS/DOS terminal appearance
- Three color schemes:
  - Blue background (#0000FF) with white text (#FFFFFF) [default]
  - White background (#FFFFFF) with black text (#000000)
  - Black background (#000000) with white text (#FFFFFF)
- Terminal-like typing effects and cursor
- Boot sequence animation on first visit
- Keyboard shortcuts for quick navigation
- Responsive design for desktop and mobile
- Left-side navigation menu with easy access to major categories
- Login option in the top right corner

## Requirements

- MediaWiki 1.35.0 or newer

## Installation

1. Download and place the BIOSTerminal folder in your MediaWiki `skins/` directory
2. Add the following line to your `LocalSettings.php`:
   ```php
   wfLoadSkin( 'BIOSTerminal' );
   ```
3. **Optional:** To make BIOSTerminal the default skin, add:
   ```php
   $wgDefaultSkin = 'biosterminal';
   ```
4. Run the MediaWiki update script to ensure all necessary changes are made:
   ```
   php maintenance/update.php
   ```

## Customization

### Changing Theme

Users can change their theme preference in their user preferences under the "Appearance" tab, or use the Alt+T keyboard shortcut to cycle through themes.

### Font

The skin uses the [MorePerfectDOSVGA](http://laemeur.sdf.org/fonts/MorePerfectDOSVGA.ttf) font by Laemeur. 

#### Font Installation:

1. Download the font file from http://laemeur.sdf.org/fonts/MorePerfectDOSVGA.ttf
2. Place it in the `resources/fonts/` directory of your skin
3. The font will be automatically loaded via the skin's resource loader

If you need to use a different font, you can:
1. Replace the TTF file in the fonts directory (keep the same filename)
2. OR modify the `resources/fonts/fonts.css` file to point to your custom font

### Directory Structure

```
BIOSTerminal/
├── i18n/                     # Internationalization files
│   └── en.json               # English translations
├── includes/                 # PHP classes
│   ├── SkinBIOSTerminal.php  # Main skin class
│   └── BIOSTerminalTemplate.php # Template class
├── resources/                # Frontend resources
│   ├── css/                  # CSS files
│   │   ├── BIOSTerminal.css  # Main CSS (Blue/White)
│   │   ├── BIOSTerminal-white-black.css # White/Black theme
│   │   └── BIOSTerminal-black-white.css # Black/White theme
│   └── js/                   # JavaScript files
│       └── biosterminal.js   # Terminal effects and interactions
├── BIOSTerminal.php          # Legacy PHP entry point
├── skin.json                 # Skin manifest
└── README.md                 # This file
```

## Keyboard Shortcuts

- `Alt+H`: Toggle help dialog
- `Alt+S`: Focus search box
- `Alt+M`: Return to main page
- `Alt+E`: Edit current page (if available)
- `Alt+T`: Toggle between theme variants

## License

This skin is licensed under the GNU General Public License v2.0 or later.

## Credits

- Font: [MorePerfectDOSVGA](http://laemeur.sdf.org/fonts/) by [Laemeur](http://laemeur.sdf.org/)
- Inspired by classic BIOS and DOS terminal interfaces
