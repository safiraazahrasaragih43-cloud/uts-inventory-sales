import json

with open('deep_candidates.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for tag in ['skirt_1', 'skirt_2']:
    print(f"=== {tag} ===")
    for it in data.get(tag, []):
        src = it.get('src', '')
        alt = it.get('alt', '')
        if 'images.unsplash.com/photo-' in src:
            print(f"  {alt} -> {src.split('?')[0]}")
