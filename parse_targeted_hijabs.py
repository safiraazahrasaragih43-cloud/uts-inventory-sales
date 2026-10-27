import json

with open('targeted_hijabs.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for q, items in data.items():
    print(f"\n=== Query: {q} ===")
    for idx, it in enumerate(items):
        src = it.get('src', '')
        alt = it.get('alt', '')
        if 'images.unsplash.com/photo-' in src:
            clean = src.split('?')[0] + "?auto=format&fit=crop&w=800&q=80"
            print(f"  [{idx}] {alt} -> {clean}")
