import json

with open('apparel_candidates.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for tag in ['linen_culottes', 'flannel_shirt', 'pleated_skirt']:
    print(f"\n=== {tag} ===")
    for idx, it in enumerate(data.get(tag, [])):
        src = it.get('src', '')
        alt = it.get('alt', '')
        if 'images.unsplash.com/photo-' in src:
            clean = src.split('?')[0] + "?auto=format&fit=crop&w=800&q=80"
            print(f"  [{idx}] {alt[:70]} -> {clean}")
