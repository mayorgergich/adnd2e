<?php
# Performance Optimization Settings

# Enable caching - this dramatically improves performance
$wgMainCacheType = CACHE_ACCEL;  # Use PHP's built-in caching
$wgParserCacheType = CACHE_ACCEL;
$wgMessageCacheType = CACHE_ACCEL;
$wgSessionCacheType = CACHE_ACCEL;

# Cache pages for anonymous users
$wgUseFileCache = true;
$wgFileCacheDirectory = "$IP/cache";
$wgShowIPinHeader = false;

# Job queue settings to prevent overloading
$wgJobRunRate = 0.01;  # Process jobs gradually

# Parser optimizations
$wgEnableSidebarCache = true;
$wgAdaptiveMessageCache = true;
$wgTransformsWithCurlyBraceContainmentDisabled = [ 'nowiki' ];

# Resource loader optimizations
$wgResourceLoaderMaxage['versioned'] = 60 * 60 * 24 * 30; # 30 days
$wgResourceLoaderMaxage['unversioned'] = 60 * 60 * 24; # 1 day

# Reduce expensive HTTP operations
$wgMiserMode = true;

# Use JSON encoder/decoder for data serialization
$wgSerializationFormat = 'json';

# Apache/Web Server optimizations
$wgUseGzip = true;  # Enable gzip compression

# Set a sensible memory limit
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 120);

# Enable APC user cache if available
if (function_exists('apc_store')) {
    $wgMainCacheType = CACHE_ACCEL;
}

# Database optimization
$wgMysqlSlave = true;  # Use replica connections for read-only queries
$wgDBmysql5 = false;   # Disable MySQL 5 compatibility mode if using MariaDB

# Create cache directory if it doesn't exist
if (!is_dir("$IP/cache")) {
    mkdir("$IP/cache", 0755, true);
}
