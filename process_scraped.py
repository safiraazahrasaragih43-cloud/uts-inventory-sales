import json
import urllib.request
import ssl
import os

ctx = ssl._create_unverified_context()
os.makedirs("curated_review", exist_ok=True)

with open("unsplash_scraped.json", "r", encoding="utf-8") as f:
    data = json.load(f)

for category, items in data.items():
    print(f"\n=== Category: {category} ===")
    for idx, item in enumerate(items[:5]):
        alt = item.get("alt", "")
        pid = item.get("photo_id")
        src = item.get("src", "")
        print(f"[{idx}] {pid} - {alt[:60]}")
        # Download image for visual review
        fname = f"curated_review/{category}_{idx}_{pid}.jpg"
        if not os.path.exists(fname):
            img_url = f"https://images.unsplash.com/photo-{pid}?auto=format&fit=crop&w=600&q=80"
            try:
                req = urllib.request.Request(img_url, headers={"User-Agent": "Mozilla/5.0"})
                with urllib.request.urlopen(req, context=ctx) as r, open(fname, "wb") as out:
                    out.write(r.read())
            except Exception as e:
                print(f"    Failed to download: {e}")
