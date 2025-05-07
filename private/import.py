import os
import requests
import json

class MediaWikiAPI:
    def __init__(self, base_url):
        self.base_url = base_url
        self.session = requests.Session()
    
    def login(self, username, password):
        # First get login token
        r1 = self.session.get(f'{self.base_url}/api.php?action=query&meta=tokens&type=login&format=json')
        login_token = r1.json()['query']['tokens']['logintoken']
        
        # Then log in
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

# Usage example:
api = MediaWikiAPI('https://adnd2e-private.mayorgergich.xyz')
api.login(os.getenv("WIKI_USER"), os.getenv("WIKI_PASS"))

with open('wiki_main_page.txt', 'r') as f:
    content = f.read()
    
result = api.edit_page('Main_Page', content, 'Updated main page structure')
print(json.dumps(result, indent=2))
