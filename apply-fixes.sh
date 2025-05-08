#!/bin/bash
# Script to apply emergency fixes to the MediaWiki container

echo "Copying emergency-fix.js..."
docker cp /opt/mediawiki/skins/BIOSTerminal/resources/js/emergency-fix.js adnd2e:/var/www/html/skins/BIOSTerminal/resources/js/

echo "Copying modified BIOSTerminalTemplate.php..."
docker cp /opt/mediawiki/skins/BIOSTerminal/includes/BIOSTerminalTemplate.php adnd2e:/var/www/html/skins/BIOSTerminal/includes/

echo "Copying modified skin.json..."
docker cp /opt/mediawiki/skins/BIOSTerminal/skin.json adnd2e:/var/www/html/skins/BIOSTerminal/

echo "Setting correct permissions..."
docker exec -it adnd2e bash -c "chown www-data:www-data /var/www/html/skins/BIOSTerminal/resources/js/emergency-fix.js"
docker exec -it adnd2e bash -c "chmod 644 /var/www/html/skins/BIOSTerminal/resources/js/emergency-fix.js"
docker exec -it adnd2e bash -c "chown www-data:www-data /var/www/html/skins/BIOSTerminal/includes/BIOSTerminalTemplate.php"
docker exec -it adnd2e bash -c "chmod 644 /var/www/html/skins/BIOSTerminal/includes/BIOSTerminalTemplate.php"
docker exec -it adnd2e bash -c "chown www-data:www-data /var/www/html/skins/BIOSTerminal/skin.json"
docker exec -it adnd2e bash -c "chmod 644 /var/www/html/skins/BIOSTerminal/skin.json"

echo "Clearing cache..."
docker exec -it adnd2e bash -c "php maintenance/run.php clearCache.php"

echo "Restarting container..."
docker restart adnd2e

echo "Done! Please allow a few moments for the container to restart, then try accessing your wiki."
