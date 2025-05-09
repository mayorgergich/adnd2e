<?php
# === CACHE SETTINGS ===
# Enable object caching
$wgMainCacheType = CACHE_ACCEL;  # Use PHP's built-in APC/APCu
$wgParserCacheType = CACHE_ACCEL;
$wgMessageCacheType = CACHE_ACCEL;

# Cache pages for anonymous users
$wgUseFileCache = true;
$wgFileCacheDirectory = "$IP/cache";
$wgShowIPinHeader = false;

# Reduce parser work
$wgEnableSidebarCache = true;

# Resource loader optimizations
$wgResourceLoaderMaxage['versioned'] = 60 * 60 * 24 * 30; # 30 days
$wgResourceLoaderMaxage['unversioned'] = 60 * 60 * 24; # 1 day

# === PERFORMANCE SETTINGS ===
# Reduce expensive operations
$wgMiserMode = true;

# Use compression
$wgUseGzip = true;

# === SKIN SETTINGS ===
# Set BIOSTerminal as the default skin
$wgDefaultSkin = 'biosterminal';

# === SECURITY ===
# Set proper file permissions to avoid permission issues
$wgDirectoryMode = 0755;  # Less restrictive for better operation
