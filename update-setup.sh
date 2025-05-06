#!/bin/bash
# Script to update MediaWiki setup to use a single shared database

echo "=== Updating MediaWiki Setup with Shared Database ==="

# Backup current configurations
echo "Creating backups of current configuration..."
mkdir -p /opt/mediawiki/backups/$(date +%Y%m%d)
cp /opt/mediawiki/docker-compose.yml /opt/mediawiki/backups/$(date +%Y%m%d)/
cp /opt/mediawiki/LocalSettings.php /opt/mediawiki/backups/$(date +%Y%m%d)/
cp /opt/mediawiki/private/docker-compose.yml /opt/mediawiki/backups/$(date +%Y%m%d)/private-docker-compose.yml
cp /opt/mediawiki/private/LocalSettings.php /opt/mediawiki/backups/$(date +%Y%m%d)/private-LocalSettings.php

# Stop existing containers
echo "Stopping existing containers..."
cd /opt/mediawiki
docker compose down
cd /opt/mediawiki/private
docker compose down

# Back up databases if they exist
echo "Backing up databases..."
if docker ps -a | grep -q "adnd2e-db"; then
    docker start adnd2e-db
    docker exec adnd2e-db mysqldump -u root -p"rootpassword" mediawiki_fresh > /opt/mediawiki/backups/$(date +%Y%m%d)/mediawiki_fresh.sql
    docker stop adnd2e-db
fi

# Start the new MariaDB service
echo "Starting shared MariaDB service..."
cd /opt/mediawiki
docker compose -f mariadb-compose.yml up -d

# Wait for MariaDB to initialize
echo "Waiting for MariaDB to initialize..."
sleep 20

# Create/ensure databases exist
echo "Setting up databases..."
docker exec mariadb mysql -u root -p"rootpassword" -e "
CREATE DATABASE IF NOT EXISTS mediawiki_fresh;
CREATE DATABASE IF NOT EXISTS mediawiki_private;
CREATE USER IF NOT EXISTS 'pawneemayor'@'%' IDENTIFIED BY 'password321';
GRANT ALL PRIVILEGES ON mediawiki_fresh.* TO 'pawneemayor'@'%';
GRANT ALL PRIVILEGES ON mediawiki_private.* TO 'pawneemayor'@'%';
FLUSH PRIVILEGES;"

# Import database backups if they exist
if [ -f "/opt/mediawiki/backups/$(date +%Y%m%d)/mediawiki_fresh.sql" ]; then
    echo "Importing backup of mediawiki_fresh database..."
    docker exec -i mariadb mysql -u root -p"rootpassword" mediawiki_fresh < /opt/mediawiki/backups/$(date +%Y%m%d)/mediawiki_fresh.sql
fi

# Start the MediaWiki containers
echo "Starting MediaWiki containers..."
cd /opt/mediawiki
docker compose up -d
cd /opt/mediawiki/private
docker compose up -d

# Update LocalSettings.php files in containers
echo "Updating LocalSettings.php in containers..."
docker cp /opt/mediawiki/LocalSettings.php adnd2e:/var/www/html/
docker exec adnd2e chmod 644 /var/www/html/LocalSettings.php
docker exec adnd2e chown www-data:www-data /var/www/html/LocalSettings.php

docker cp /opt/mediawiki/private/LocalSettings.php adnd2e-private:/var/www/html/
docker exec adnd2e-private chmod 644 /var/www/html/LocalSettings.php
docker exec adnd2e-private chown www-data:www-data /var/www/html/LocalSettings.php

# Create cache directories
echo "Creating cache directories..."
docker exec adnd2e bash -c "mkdir -p /var/www/html/cache && chown -R www-data:www-data /var/www/html/cache"
docker exec adnd2e-private bash -c "mkdir -p /var/www/html/cache && chown -R www-data:www-data /var/www/html/cache"

# Ensure proper permissions for image directories
echo "Setting permissions for image directories..."
docker exec adnd2e chown -R www-data:www-data /var/www/html/images
docker exec adnd2e chmod -R 755 /var/www/html/images
docker exec adnd2e-private chown -R www-data:www-data /var/www/html/images
docker exec adnd2e-private chmod -R 755 /var/www/html/images

# Run update script to ensure database structure is current
echo "Running update script for both wikis..."
docker exec -u www-data adnd2e php maintenance/update.php --quick
docker exec -u www-data adnd2e-private php maintenance/update.php --quick

# Restart containers
echo "Restarting containers to apply all changes..."
docker restart adnd2e
docker restart adnd2e-private

echo "=== Setup complete! ==="
echo "Your wikis should now be available at:"
echo "  * https://adnd2e.mayorgergich.xyz"
echo "  * https://adnd2e-private.mayorgergich.xyz"
echo ""
echo "Both wikis are now using the shared MariaDB database container."
