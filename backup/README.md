# MediaWiki Performance Optimization Backup

This directory contains backups of configuration files and scripts used to optimize the MediaWiki installation.

## Contents

- `config/` - MediaWiki configuration files
  - `LocalSettings.php` - Main MediaWiki configuration
  - `performance_settings.txt` - Extract of performance settings
- `docker/` - Docker container configuration files
  - `mariadb-custom.cnf` - MariaDB performance settings
  - `apache_*.conf` - Apache configuration files
- `scripts/` - Utility scripts
  - `performance_fixes.sh` - Script to apply performance optimizations

## Usage

If you need to restore or reapply these optimizations, you can use the `performance_fixes.sh` script as a reference.

## Performance Settings

The key performance optimizations include:
- PHP memory increased to 1GB
- Database query caching enabled
- Parser and message caching configured
- Resource caching optimized
- MariaDB tuned for better performance

Note: File cache was disabled to prevent corruption issues.
