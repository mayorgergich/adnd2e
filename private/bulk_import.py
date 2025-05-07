import os
import requests
import json

class MediaWikiAPI:
    def __init__(self, base_url):
        self.base_url = base_url
        self.session = requests.Session()
    
    def login(self, username, password):
        r1 = self.session.get(f'{self.base_url}/api.php?action=query&meta=tokens&type=login&format=json')
        login_token = r1.json()['query']['tokens']['logintoken']
        
        login_data = {
            'action': 'login',
            'lgname': username,
            'lgpassword': password,
            'lgtoken': login_token,
            'format': 'json'
        }
        r2 = self.session.post(f'{self.base_url}/api.php', data=login_data)
        return r2.json()

    def get_csrf_token(self):
        r = self.session.get(f'{self.base_url}/api.php?action=query&meta=tokens&format=json')
        return r.json()['query']['tokens']['csrftoken']

    def edit_page(self, title, content, summary):
        token = self.get_csrf_token()
        edit_data = {
            'action': 'edit',
            'title': title,
            'text': content,
            'token': token,
            'format': 'json',
            'summary': summary
        }
        r = self.session.post(f'{self.base_url}/api.php', data=edit_data)
        return r.json()

def process_files(api, base_path):
    for root, dirs, files in os.walk(base_path):
        for file in files:
            if file.endswith('.mediawiki'):
                file_path = os.path.join(root, file)
                # Create wiki page title from the file path
                wiki_title = os.path.relpath(file_path, base_path)
                wiki_title = wiki_title[:-10]  # Remove .mediawiki extension
                wiki_title = wiki_title.replace('/', ':')  # Use : for namespace separation
                
                with open(file_path, 'r') as f:
                    content = f.read()
                
                print(f"Importing: {wiki_title}")
                result = api.edit_page(wiki_title, content, f'Imported from {file_path}')
                print(json.dumps(result, indent=2))

api = MediaWikiAPI('https://adnd2e-private.mayorgergich.xyz')
api.login(os.getenv("WIKI_USER"), os.getenv("WIKI_PASS"))
process_files(api, 'wiki')
