import urllib.request
import json

def search_openverse(query, tags=None):
    url = f"https://api.openverse.org/v1/images/?q={urllib.parse.quote(query)}&page_size=10"
    req = urllib.request.Request(url, headers={'User-Agent': 'BlushBoutiqueApp/1.0'})
    try:
        with urllib.request.urlopen(req, timeout=10) as response:
            data = json.loads(response.read().decode('utf-8'))
            results = []
            for item in data.get('results', []):
                results.append({
                    'id': item.get('id'),
                    'title': item.get('title'),
                    'url': item.get('url'),
                    'creator': item.get('creator'),
                    'source': item.get('source'),
                    'license': item.get('license')
                })
            return results
    except Exception as e:
        print(f"Error {query}: {e}")
        return []

items = search_openverse("hijab")
print(f"Found {len(items)} items for hijab:")
for it in items[:5]:
    print(f"  {it['title']} | {it['source']} | {it['url']}")
