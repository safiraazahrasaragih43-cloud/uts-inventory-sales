import urllib.request
import json

url = "https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch=hijab&srnamespace=6&format=json"
req = urllib.request.Request(url, headers={'User-Agent': 'BlushBoutiqueApp/1.0 (contact@blushboutique.com)'})
with urllib.request.urlopen(req, timeout=10) as resp:
    data = json.loads(resp.read().decode('utf-8'))
    print(json.dumps(data, indent=2)[:500])
