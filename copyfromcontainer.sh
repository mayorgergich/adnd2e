#!/bin/bash
# Script to copy fixed files from container to /opt/mediawiki on host

# Backup original files
echo "Creating backups in /opt/mediawiki..."
sudo mkdir -p /opt/mediawiki/backups/$(date +%Y%m%d)
sudo cp -v /opt/mediawiki/skins/BIOSTerminal/skin.json /opt/mediawiki/backups/$(date +%Y%m%d)/skin.json.bak 2>/dev/null || echo "No existing skin.json to backup"

# Copy fixed skin.json from container to host directory
echo "Copying fixed skin.json from container to host..."
docker cp adnd2e:/var/www/html/skins/BIOSTerminal/skin.json /opt/mediawiki/skins/BIOSTerminal/skin.json
docker cp adnd2e:/var/www/html/skins/BIOSTerminal/ /opt/mediawiki/skins/BIOSTerminal/
# Copy LocalSettings.php if needed
echo "Copying LocalSettings.php from container to host..."
docker cp adnd2e:/var/www/html/LocalSettings.php /opt/mediawiki/LocalSettings.php.container

# Fix permissions
echo "Setting correct permissions..."
sudo chown -R 1000:1000 /opt/mediawiki/skins/BIOSTerminal/
sudo chmod -R 755 /opt/mediawiki/skins/BIOSTerminal/
sudo chmod 644 /opt/mediawiki/skins/BIOSTerminal/skin.json
sudo chown 1000:1000 /opt/mediawiki/LocalSettings.php.container
sudo chmod 644 /opt/mediawiki/LocalSettings.php.container

echo "Files copied from container to /opt/mediawiki successfully!"
