{
    "name": "BIOSTerminal",
    "version": "1.0.0",
    "author": [
        "Your Name"
    ],
    "url": "https://github.com/your-repo/BIOSTerminal",
    "descriptionmsg": "biosterminal-skin-desc",
    "namemsg": "skinname-biosterminal",
    "license-name": "GPL-2.0-or-later",
    "type": "skin",
    "requires": {
        "MediaWiki": ">= 1.35.0"
    },
    "ValidSkinNames": {
        "biosterminal": {
            "class": "SkinBIOSTerminal",
            "args": []
        }
    },
    "MessagesDirs": {
        "BIOSTerminal": [
            "i18n"
        ]
    },
    "ResourceModules": {
        "skins.biosterminal.fonts": {
            "styles": {
                "resources/fonts/fonts.css": {
                    "media": "all"
                }
            }
        },
        "skins.biosterminal.styles": {
            "styles": {
                "resources/css/BIOSTerminal.css": {
                    "media": "all"
                }
            },
            "dependencies": [
                "skins.biosterminal.fonts"
            ]
        },
        "skins.biosterminal.scripts": {
            "scripts": [
                "resources/js/biosterminal.js"
            ],
            "dependencies": [
                "mediawiki.util",
                "jquery"
            ]
        },
        "skins.biosterminal": {
            "class": "MediaWiki\\ResourceLoader\\SkinModule",
            "features": [
                "elements",
                "content",
                "interface",
                "logo",
                "legacy"
            ],
            "targets": [
                "desktop",
                "mobile"
            ],
            "dependencies": [
                "skins.biosterminal.styles",
                "skins.biosterminal.scripts"
            ]
        }
    },
    "ResourceFileModulePaths": {
        "localBasePath": "",
        "remoteSkinPath": "BIOSTerminal"
    },
    "AutoloadClasses": {
        "SkinBIOSTerminal": "includes/SkinBIOSTerminal.php",
        "BIOSTerminalTemplate": "includes/BIOSTerminalTemplate.php"
    },
    "manifest_version": 2
}
