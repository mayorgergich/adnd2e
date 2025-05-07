#!/bin/bash

# Create backup
echo "Creating backup..."
sudo tar -czf mariadb_backup_$(date +%Y%m%d).tar.gz /opt/mariadb
docker inspect mariadb > mariadb_container_config_$(date +%Y%m%d).json

# Copy the new configuration file
echo "Copying new configuration..."
sudo cp my.cnf /opt/mariadb/custom.cnf

# Update container configuration
echo "Updating container configuration..."
docker update \
  --memory="2G" \
  --memory-reservation="1G" \
  mariadb

# Restart the container to apply changes
echo "Restarting container to apply changes..."
docker restart mariadb

echo "Done! Please check the logs for any errors:"
echo "docker logs mariadb" 