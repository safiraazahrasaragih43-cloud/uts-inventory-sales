import urllib.request
import json

def search_commons(query, limit=5):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrnamespace=6&gsrsearch={urllib.parse.quote(query)}&gsrlimit={limit}&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': 'BlushBoutiqueApp/1.0 (contact@blushboutique.com)'})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            results = []
            for pid, p in pages.items():
                title = p.get('title', '')
                ii = p.get('imageinfo', [{}])[0]
                img_url = ii.get('url', '')
                desc = ii.get('extmetadata', {}).get('ObjectName', {}).get('value', '') or title
                if img_url.lower().endswith(('.jpg', '.jpeg', '.png', '.webp')):
                    results.append({'title': title, 'url': img_url, 'desc': desc})
            return results
    except Exception as e:
        print(f"Error: {e}")
        return []

items = search_commons("hijab fashion woman", 5)
print(f"Found {len(items)} items:")
for it in items:
    print(f"  {it['title']}\n  {it['url']}")
