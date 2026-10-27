from playwright.sync_api import sync_playwright
import json
import urllib.request
import ssl
import os

ctx = ssl._create_unverified_context()
os.makedirs("free_curated", exist_ok=True)

queries = [
    ("dress_flower", "https://unsplash.com/s/photos/dress-flat-lay?license=free"),
    ("dress_hanging", "https://unsplash.com/s/photos/dress-on-hanger?license=free"),
    ("dress_evening", "https://unsplash.com/s/photos/evening-dress-hanger?license=free"),
    ("blouse_linen", "https://unsplash.com/s/photos/white-blouse-flat-lay?license=free"),
    ("skirt_pink", "https://unsplash.com/s/photos/skirt-flat-lay?license=free"),
    ("skirt_hang", "https://unsplash.com/s/photos/skirt-hanger?license=free"),
    ("culottes_linen", "https://unsplash.com/s/photos/linen-trousers-flat-lay?license=free"),
    ("cardigan_pastel", "https://unsplash.com/s/photos/cardigan-hanger?license=free"),
    ("knit_sweater", "https://unsplash.com/s/photos/folded-sweater?license=free"),
    ("hijab_square", "https://unsplash.com/s/photos/folded-scarf?license=free"),
    ("hijab_silk", "https://unsplash.com/s/photos/silk-fabric-drape?license=free"),
]

scraped = {}

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    page = browser.new_page()

    for name, url in queries:
        print(f"Searching {name}: {url}")
        try:
            page.goto(url, wait_until="domcontentloaded", timeout=20000)
            page.wait_for_timeout(3000)
            page.evaluate("window.scrollBy(0, 800)")
            page.wait_for_timeout(1500)

            items = page.evaluate("""() => {
                const imgs = Array.from(document.querySelectorAll('figure img'));
                const list = [];
                for (const img of imgs) {
                    const src = img.src || '';
                    const srcset = img.srcset || '';
                    const full = src + ' ' + srcset;
                    if (full.includes('plus.unsplash.com')) continue; // Skip plus / premium
                    const m = full.match(/images\.unsplash\.com\/photo-([0-9a-zA-Z_-]+)/);
                    if (m) {
                        list.push({
                            pid: m[1],
                            alt: img.alt || ''
                        });
                    }
                }
                return list;
            }""")
            print(f"  Found {len(items)} free items")
            scraped[name] = items[:10]
        except Exception as e:
            print(f"  Error on {name}: {e}")

    browser.close()

with open("free_unsplash.json", "w", encoding="utf-8") as f:
    json.dump(scraped, f, indent=2)

print("Scrape complete! Now downloading candidates...")

for cat, items in scraped.items():
    for idx, it in enumerate(items[:4]):
        pid = it["pid"]
        alt = it["alt"]
        fname = f"free_curated/{cat}_{idx}_{pid}.jpg"
        if not os.path.exists(fname):
            url = f"https://images.unsplash.com/photo-{pid}?auto=format&fit=crop&w=600&q=80"
            try:
                req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
                with urllib.request.urlopen(req, context=ctx) as r, open(fname, "wb") as f:
                    f.write(r.read())
                print(f"  Saved {fname} ({alt[:40]})")
            except Exception as e:
                print(f"  Download error {pid}: {e}")
