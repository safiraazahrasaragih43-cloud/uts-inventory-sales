import json

with open('deep_candidates.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for tag, items in data.items():
    print(f"\n================ {tag} ================")
    count = 0
    for idx, it in enumerate(items):
        src = it.get('src', '')
        alt = it.get('alt', '')
        # Only community images (not plus.unsplash.com)
        if 'images.unsplash.com/photo-' in src:
            clean_url = src.split('?')[0] + "?auto=format&fit=crop&w=800&q=80"
            print(f"  [{count}] ALT: {alt}")
            print(f"       URL: {clean_url}")
            count += 1
            if count >= 3:
                break
