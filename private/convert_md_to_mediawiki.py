#!/usr/bin/env python3

import os
import glob
import hashlib
import shutil
from datetime import datetime

def get_md5(filepath):
    """Calculate MD5 hash of file content."""
    with open(filepath, 'rb') as f:
        return hashlib.md5(f.read()).hexdigest()

def main():
    """Find .md files and move them to a backup folder outside the wiki directory."""
    wiki_dir = 'wiki'
    
    # Create backup directory with timestamp
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    backup_dir = f'md_backup_{timestamp}'
    os.makedirs(backup_dir, exist_ok=True)
    print(f"Created backup directory: {backup_dir}\n")
    
    # Find all .md files
    md_files = glob.glob(f'{wiki_dir}/**/*.md', recursive=True)
    
    md_with_mediawiki = []
    md_without_mediawiki = []
    
    for md_file in md_files:
        mediawiki_file = md_file.replace('.md', '.mediawiki')
        
        if os.path.exists(mediawiki_file):
            md_with_mediawiki.append((md_file, mediawiki_file))
        else:
            md_without_mediawiki.append(md_file)
    
    # Report findings
    print(f"Found {len(md_files)} .md files in total")
    print(f"- {len(md_with_mediawiki)} have corresponding .mediawiki files")
    print(f"- {len(md_without_mediawiki)} don't have corresponding .mediawiki files\n")
    
    # Move all .md files to backup directory
    if md_files:
        moved_count = 0
        print(f"Moving .md files to {backup_dir}:")
        
        for md_file in md_files:
            # Create the same directory structure in backup
            rel_path = os.path.relpath(md_file, wiki_dir)
            backup_path = os.path.join(backup_dir, rel_path)
            
            # Create directory structure
            os.makedirs(os.path.dirname(backup_path), exist_ok=True)
            
            # Move file
            print(f"Moving {md_file} to {backup_path}")
            shutil.copy2(md_file, backup_path)
            os.remove(md_file)
            moved_count += 1
        
        print(f"\nSuccessfully moved {moved_count} .md files to {backup_dir}")
        
        # For files without .mediawiki equivalent, copy from backup to .mediawiki
        if md_without_mediawiki:
            print("\nCreating .mediawiki files for .md files that don't have equivalents:")
            
            for md_file in md_without_mediawiki:
                mediawiki_file = md_file.replace('.md', '.mediawiki')
                backup_path = os.path.join(backup_dir, os.path.relpath(md_file, wiki_dir))
                
                print(f"Copying {backup_path} to {mediawiki_file}")
                shutil.copy2(backup_path, mediawiki_file)
    else:
        print("No .md files found")

if __name__ == "__main__":
    main() 