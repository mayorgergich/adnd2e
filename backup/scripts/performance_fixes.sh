#!/bin/bash
# Script to apply performance optimizations to MediaWiki

# Performance optimizations for LocalSettings.php
docker exec -it adnd2e bash -c "cat >> /var/www/html/LocalSettings.php << 'EOTT'
# Performance Optimizations
\$wgMainCacheType = CACHE_ACCEL;
\$wgParserCacheType = CACHE_ACCEL;
\$wgMessageCacheType = CACHE_ACCEL;
\$wgUseFileCache = false;  # Set to false to prevent corruption
\$wgFileCacheDirectory = \"\$IP/cache\";
\$wgResourceLoaderMaxage['versioned'] = 60 * 60 * 24 * 30;
\$wgResourceLoaderMaxage['unversioned'] = 60 * 60 * 24;
\$wgEnableSidebarCache = true;
\$wgAdaptiveMessageCache = true;
\$wgUseGzip = true;

# Set memory limits higher for robust servers
ini_set('memory_limit', '1G');
ini_set('max_execution_time', 300);

# Database optimizations
\$wgMysqlSlave = true; # Use replica connections for read-only queries
\$wgDBmysql5 = false;  # Disable if using MariaDB 10+
EOTT"

# Create cache directory
docker exec -it adnd2e mkdir -p /var/www/html/cache
docker exec -it adnd2e chown -R www-data:www-data /var/www/html/cache
docker exec -it adnd2e chmod 755 /var/www/html/cache

# MariaDB performance configuration
cat > /opt/mediawiki/mariadb-custom.cnf << 'EOTT'
[mysqld]
innodb_buffer_pool_size = 4G
innodb_log_file_size = 512M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
max_connections = 200
query_cache_type = 1
query_cache_size = 128M
thread_cache_size = 128
tmp_table_size = 256M
max_heap_table_size = 256M
EOTT

# Copy it to MariaDB container and restart
docker cp /opt/mediawiki/mariadb-custom.cnf mariadb:/etc/mysql/conf.d/
docker restart mariadb

# Run update script and rebuild caches
docker exec -it adnd2e php maintenance/run.php update.php --quick
docker exec -it adnd2e php maintenance/run.php rebuildLocalisationCache.php

# Fix permissions
docker exec -it adnd2e chown -R www-data:www-data /var/www/html/cache
docker exec -it adnd2e chown www-data:www-data /var/www/html/LocalSettings.php
docker exec -it adnd2e chmod 644 /var/www/html/LocalSettings.php

# Restart the containers
docker restart adnd2e
