import urllib.request
import urllib.parse
import ssl
import json
import re

ctx = ssl._create_unverified_context()

def search_gh_code(query):
    url = f"https://api.github.com/search/code?q={urllib.parse.quote(query)}&per_page=15"
    req = urllib.request.Request(url, headers={
        "User-Agent": "Mozilla/5.0",
        "Accept": "application/vnd.github.v3+json"
    })
    try:
        with urllib.request.urlopen(req, context=ctx) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            items = data.get("items", [])
            print(f"Query '{query}' -> {len(items)} items")
            return items
    except Exception as e:
        print(f"Error on {query}: {e}")
        return []

items = search_gh_code('images.unsplash.com/photo- "skirt" in:file extension:json')
for it in items[:5]:
    print(it.get('html_url'))
