import json
import urllib.request
import os

with open('candidates.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

os.makedirs('candidates_preview', exist_ok=True)

summary = {}
for prod_key, items in data.items():
    print(f"\n=== {prod_key} ({len(items)} candidates) ===")
    summary[prod_key] = []
    for idx, it in enumerate(items[:4]):
        src = it.get('src')
        alt = it.get('alt', '')
        # Clean Unsplash URL to a reasonable width (w=600)
        # Unsplash src typically has ?fm=jpg&q=60&w=3000...
        base_url = src.split('?')[0]
        cleaned_url = f"{base_url}?auto=format&fit=crop&w=600&q=80"
        filename = f"candidates_preview/{prod_key}_{idx}.jpg"
        print(f"  [{idx}] {alt[:70]}")
        print(f"      {cleaned_url}")
        
        # Download preview image if not exists
        if not os.path.exists(filename):
            try:
                urllib.request.urlretrieve(cleaned_url, filename)
            except Exception as e:
                print(f"      Download failed: {e}")
        
        summary[prod_key].append({
            'idx': idx,
            'alt': alt,
            'url': cleaned_url,
            'file': filename
        })

with open('preview_summary.json', 'w', encoding='utf-8') as f:
    json.dump(summary, f, indent=2, ensure_ascii=False)
