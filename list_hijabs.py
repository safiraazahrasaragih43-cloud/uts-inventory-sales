import json

with open('deep_candidates.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

for tag in ['hijab_1', 'hijab_2', 'hijab_3', 'pashmina_1', 'pashmina_2', 'pashmina_3']:
    print(f"=== {tag} ===")
    for idx, item in enumerate(data.get(tag, [])):
        src = item.get('src', '')
        alt = item.get('alt', '')
        if 'images.unsplash.com/photo-' in src:
            clean = src.split('?')[0] + "?auto=format&fit=crop&w=800&q=80"
            print(f"  [{idx}] {alt} -> {clean}")
