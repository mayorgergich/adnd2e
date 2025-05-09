#!/bin/bash

echo "=== MariaDB Maintenance for MediaWiki ==="

# Optimize tables
echo "Optimizing MediaWiki tables..."
docker exec -it mariadb mysql -e "OPTIMIZE TABLE adnd2e_db.page, adnd2e_db.revision, adnd2e_db.text, adnd2e_db.categorylinks;"

# Clear potentially stale locks
echo "Checking for stale locks..."
docker exec -it mariadb mysql -e "SHOW OPEN TABLES FROM adnd2e_db WHERE In_use > 0;"

# Run MediaWiki's own maintenance scripts
echo "Running MediaWiki maintenance scripts..."
docker exec -it adnd2e php /var/www/html/maintenance/update.php --quick
docker exec -it adnd2e php /var/www/html/maintenance/rebuildrecentchanges.php

echo "Maintenance complete!"
