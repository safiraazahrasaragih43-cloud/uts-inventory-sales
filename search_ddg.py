import urllib.request
import urllib.parse
import ssl
import re

ctx = ssl._create_unverified_context()

def search(query):
    url = 'https://html.duckduckgo.com/html/?q=' + urllib.parse.quote(query)
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            html = resp.read().decode('utf-8')
            matches = re.findall(r'href="([^"]*unsplash\.com/photos/[^"]*)"', html)
            clean = []
            for m in matches:
                if 'uddg=' in m:
                    actual = urllib.parse.unquote(m.split('uddg=')[1].split('&')[0])
                    clean.append(actual)
                else:
                    clean.append(m)
            return list(dict.fromkeys(clean))
    except Exception as e:
        print("Error:", e)
        return []

items = [
    "site:unsplash.com/photos \"dress\" \"flat lay\" -model -woman -person",
    "site:unsplash.com/photos \"pink dress\" hanger -model -woman",
    "site:unsplash.com/photos \"blouse\" flat lay -model -woman",
    "site:unsplash.com/photos \"skirt\" flat lay -model -woman",
    "site:unsplash.com/photos \"culottes\" OR \"linen pants\" flat lay hanger",
    "site:unsplash.com/photos \"cardigan\" flat lay hanger -woman",
    "site:unsplash.com/photos \"knit sweater\" flat lay folded",
    "site:unsplash.com/photos \"silk scarf\" flat lay OR folded",
    "site:unsplash.com/photos \"pashmina\" OR \"cashmere scarf\" flat lay",
]

for item in items:
    print("Query:", item)
    res = search(item)
    for r in res[:4]:
        print("  ->", r)
