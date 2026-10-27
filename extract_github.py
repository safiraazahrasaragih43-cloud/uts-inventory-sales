import urllib.request
import ssl
import re

ctx = ssl._create_unverified_context()

def get_links(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req, context=ctx) as resp:
            text = resp.read().decode('utf-8')
            matches = re.findall(r'https://images\.unsplash\.com/photo-[a-zA-Z0-9_\-]+[^\s"\'<>]*', text)
            return list(set(matches))
    except Exception as e:
        print("Error on", url, e)
        return []

urls = [
    'https://raw.githubusercontent.com/tapascript/fake-apis/main/db.json'
]

all_links = set()
for u in urls:
    links = get_links(u)
    print(f"URL: {u} -> {len(links)} links")
    for l in links:
        all_links.add(l)

print(f"\nTotal unique Unsplash links: {len(all_links)}")
for l in sorted(all_links):
    print(l)

