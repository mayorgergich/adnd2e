import os
import requests
import json
import sys
import time
from typing import Optional, Dict, Any, Tuple
from requests.exceptions import RequestException
import logging
import hashlib

# Set up logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

class MediaWikiAPIError(Exception):
    """Custom exception for MediaWiki API errors"""
    pass

class MediaWikiAPI:
    def __init__(self, base_url: str, request_delay: float = 0.0):
        # Ensure the base_url doesn't end with a slash
        self.base_url = base_url.rstrip('/')
        self.session = requests.Session()
        self.request_delay = request_delay
        self.last_request_time = 0
        
    def _wait_for_rate_limit(self):
        """Ensure minimum delay between requests"""
        # No-op when request_delay is 0
        if self.request_delay <= 0:
            return
            
        now = time.time()
        time_since_last_request = now - self.last_request_time
        if time_since_last_request < self.request_delay:
            time.sleep(self.request_delay - time_since_last_request)
        self.last_request_time = time.time()
    
    def _make_request(self, method: str, endpoint: str, **kwargs) -> Dict[str, Any]:
        """Make an API request with error handling"""
        self._wait_for_rate_limit()
        
        # Ensure endpoint starts with a slash
        if not endpoint.startswith('/'):
            endpoint = '/' + endpoint
            
        url = f'{self.base_url}{endpoint}'
        logger.debug(f"Making {method} request to: {url}")
        
        try:
            if method.lower() == 'get':
                response = self.session.get(url, **kwargs)
            else:
                response = self.session.post(url, **kwargs)
            
            response.raise_for_status()
            
            try:
                result = response.json()
                if 'error' in result:
                    error_msg = result['error'].get('info', str(result['error']))
                    logger.error(f"API Error: {error_msg}")
                    raise MediaWikiAPIError(f"API Error: {error_msg}")
                return result
            except json.JSONDecodeError:
                logger.error(f"Failed to parse JSON response. Response text: {response.text[:200]}...")
                raise MediaWikiAPIError("Failed to parse API response")
                
        except RequestException as e:
            logger.error(f"API request failed: {str(e)}")
            logger.debug(f"Request URL: {url}")
            raise MediaWikiAPIError(f"API request failed: {str(e)}")
    
    def login(self, username: Optional[str] = None, password: Optional[str] = None) -> Dict[str, Any]:
        """Login to MediaWiki API with error handling"""
        if username is None:
            username = os.getenv("WIKI_USER")
        if password is None:
            password = os.getenv("WIKI_PASS")
            
        if not username or not password:
            raise MediaWikiAPIError("Username and password must be provided either directly or through environment variables")
        
        logger.info(f"Attempting to log in as user: {username}")
        logger.debug(f"Using API endpoint: {self.base_url}/api.php")
            
        try:
            # Get login token
            login_token_data = self._make_request('get', '/api.php', params={
                'action': 'query',
                'meta': 'tokens',
                'type': 'login',
                'format': 'json'
            })
            
            if 'query' not in login_token_data or 'tokens' not in login_token_data['query']:
                logger.error(f"Unexpected login token response: {json.dumps(login_token_data)}")
                raise MediaWikiAPIError("Failed to get login token - unexpected response format")
                
            login_token = login_token_data['query']['tokens']['logintoken']
            
            # Perform login
            login_data = {
                'action': 'login',
                'lgname': username,
                'lgpassword': password,
                'lgtoken': login_token,
                'format': 'json'
            }
            result = self._make_request('post', '/api.php', data=login_data)
            
            # Verify login success
            if 'login' not in result:
                logger.error(f"Unexpected login response: {json.dumps(result)}")
                raise MediaWikiAPIError("Login failed - unexpected response format")
                
            if result['login']['result'] != 'Success':
                error_msg = result['login'].get('reason', 'Unknown error')
                logger.error(f"Login failed: {error_msg}")
                raise MediaWikiAPIError(f"Login failed: {error_msg}")
                
            logger.info("Successfully logged in")
            return result
            
        except (KeyError, json.JSONDecodeError) as e:
            logger.error(f"Failed to parse API response: {str(e)}")
            raise MediaWikiAPIError(f"Failed to parse API response: {str(e)}")

    def get_csrf_token(self) -> str:
        """Get CSRF token with error handling"""
        try:
            result = self._make_request('get', '/api.php', params={
                'action': 'query',
                'meta': 'tokens',
                'format': 'json'
            })
            return result['query']['tokens']['csrftoken']
        except (KeyError, json.JSONDecodeError) as e:
            logger.error(f"Failed to get CSRF token: {str(e)}")
            raise MediaWikiAPIError(f"Failed to get CSRF token: {str(e)}")
            
    def get_page_content(self, title: str) -> Tuple[bool, str]:
        """Get the content of a page
        
        Returns:
            Tuple[bool, str]: (exists, content) - exists is True if page exists, False otherwise
                               content is the page content if exists is True, empty string otherwise
        """
        try:
            result = self._make_request('get', '/api.php', params={
                'action': 'query',
                'prop': 'revisions',
                'titles': title,
                'rvprop': 'content',
                'rvslots': 'main',
                'formatversion': '2',
                'format': 'json'
            })
            
            if 'query' not in result or 'pages' not in result['query']:
                logger.error(f"Unexpected page content response: {json.dumps(result)}")
                return False, ""
                
            # Check if page exists
            page = result['query']['pages'][0]
            if 'missing' in page:
                logger.debug(f"Page {title} does not exist")
                return False, ""
                
            if 'revisions' not in page:
                logger.error(f"No revisions found for page {title}")
                return False, ""
                
            content = page['revisions'][0]['slots']['main']['content']
            return True, content
            
        except (KeyError, IndexError, json.JSONDecodeError) as e:
            logger.error(f"Failed to get page content for {title}: {str(e)}")
            return False, ""

    def edit_page(self, title: str, content: str, summary: str) -> Dict[str, Any]:
        """Edit a page with error handling"""
        try:
            token = self.get_csrf_token()
            edit_data = {
                'action': 'edit',
                'title': title,
                'text': content,
                'token': token,
                'format': 'json',
                'summary': summary
            }
            result = self._make_request('post', '/api.php', data=edit_data)
            
            if 'error' in result:
                raise MediaWikiAPIError(f"Edit failed: {json.dumps(result)}")
                
            return result
            
        except (KeyError, json.JSONDecodeError) as e:
            logger.error(f"Failed to edit page: {str(e)}")
            raise MediaWikiAPIError(f"Failed to edit page: {str(e)}")

def calculate_md5(content: str) -> str:
    """Calculate MD5 hash of content"""
    return hashlib.md5(content.encode('utf-8')).hexdigest()

def process_files(api: MediaWikiAPI, directory_path: str, force_update: bool = False) -> None:
    """Process files with error handling
    
    Args:
        api: MediaWikiAPI instance
        directory_path: Directory containing .mediawiki files
        force_update: If True, update all pages regardless of content changes
    """
    abs_path = os.path.abspath(directory_path)
    
    if not os.path.exists(abs_path):
        raise FileNotFoundError(f"Directory {directory_path} does not exist")

    logger.info(f"Starting import from directory: {abs_path}")
    
    # Statistics counters
    stats = {
        'total': 0,
        'updated': 0,
        'skipped': 0,
        'created': 0,
        'failed': 0
    }
    
    for root, _, files in os.walk(abs_path):
        for file in files:
            if not file.endswith('.mediawiki'):
                continue
                
            file_path = os.path.join(root, file)
            wiki_title = os.path.relpath(file_path, abs_path)
            wiki_title = wiki_title[:-10]  # Remove .mediawiki extension
            wiki_title = wiki_title.replace('/', ':')  # Use : for namespace separation
            
            stats['total'] += 1
            
            try:
                with open(file_path, 'r', encoding='utf-8') as f:
                    local_content = f.read()
                
                # Check if page exists and get current content
                exists, remote_content = api.get_page_content(wiki_title)
                
                # Calculate content hashes for comparison
                local_hash = calculate_md5(local_content)
                remote_hash = calculate_md5(remote_content) if exists else ""
                
                # Determine if update is needed
                needs_update = force_update or not exists or local_hash != remote_hash
                
                if needs_update:
                    action = "Creating" if not exists else "Updating"
                    logger.info(f"{action}: {wiki_title}")
                    
                    result = api.edit_page(wiki_title, local_content, f'Imported from {file_path}')
                    
                    if exists:
                        stats['updated'] += 1
                    else:
                        stats['created'] += 1
                        
                    logger.info(f"Successfully {action.lower()} {wiki_title}")
                    logger.debug(json.dumps(result, indent=2))
                else:
                    logger.info(f"Skipping {wiki_title} (no changes)")
                    stats['skipped'] += 1
                
            except (IOError, UnicodeDecodeError) as e:
                logger.error(f"Failed to read file {file_path}: {str(e)}")
                stats['failed'] += 1
                continue
            except MediaWikiAPIError as e:
                logger.error(f"Failed to import {wiki_title}: {str(e)}")
                stats['failed'] += 1
                continue
    
    # Print statistics
    logger.info("Import completed. Summary:")
    logger.info(f"  Total files: {stats['total']}")
    logger.info(f"  Created: {stats['created']}")
    logger.info(f"  Updated: {stats['updated']}")
    logger.info(f"  Skipped (unchanged): {stats['skipped']}")
    logger.info(f"  Failed: {stats['failed']}")

def main() -> None:
    """Main function with argument validation"""
    try:
        if len(sys.argv) < 2:
            print("Usage: python3 bulk_import_dir.py <directory_path> [--force]")
            print("Options:")
            print("  --force    Force update all pages regardless of content changes")
            sys.exit(1)
            
        directory_path = sys.argv[1]
        force_update = "--force" in sys.argv
        
        # Make sure we're using the correct URL for your private wiki
        # No rate limiting (request_delay=0)
        api = MediaWikiAPI('https://adnd2e-private.mayorgergich.xyz')
        
        # Set logging to INFO level (or DEBUG for more details)
        logger.setLevel(logging.INFO)
        
        # Attempt login
        api.login()
        
        # Process files
        process_files(api, directory_path, force_update)
        
    except MediaWikiAPIError as e:
        logger.error(f"API Error: {str(e)}")
        sys.exit(1)
    except Exception as e:
        logger.error(f"Unexpected error: {str(e)}")
        sys.exit(1)

if __name__ == "__main__":
    main()
