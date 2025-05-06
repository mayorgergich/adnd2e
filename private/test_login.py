import os
import logging
from bulk_import_dir import MediaWikiAPI

# Set up logging
logging.basicConfig(
    level=logging.DEBUG,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def main():
    # Create API instance
    api = MediaWikiAPI('https://adnd2e-private.mayorgergich.xyz')
    
    # Try to login
    try:
        username = os.getenv("WIKI_USER", "Admin")
        password = os.getenv("WIKI_PASS", "SecureAdminPass123")
        
        logger.info(f"Attempting to login with username: {username}")
        result = api.login(username, password)
        logger.info("Login successful!")
        logger.debug(f"Login result: {result}")
        
    except Exception as e:
        logger.error(f"Login failed: {str(e)}")
        raise

if __name__ == "__main__":
    main() 