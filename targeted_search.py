from playwright.sync_api import sync_playwright
import json
import urllib.request
import ssl
import os

ctx = ssl._create_unverified_context()
os.makedirs("targeted_curated", exist_ok=True)

queries = [
    ("floral_dress", "https://unsplash.com/s/photos/floral-midi-dress?license=free"),
    ("floral_dress_2", "https://unsplash.com/s/photos/floral-dress-on-hanger?license=free"),
    ("maroon_dress", "https://unsplash.com/s/photos/red-dress-on-hanger?license=free"),
    ("maroon_dress_2", "https://unsplash.com/s/photos/burgundy-dress-hanger?license=free"),
    ("pleated_skirt", "https://unsplash.com/s/photos/pleated-skirt?license=free"),
    ("pink_skirt", "https://unsplash.com/s/photos/pink-skirt-hanger?license=free"),
    ("voile_hijab", "https://unsplash.com/s/photos/silk-scarf-folded?license=free"),
    ("pink_scarf", "https://unsplash.com/s/photos/pink-scarf-flat-lay?license=free"),
    ("lavender_cardigan", "https://unsplash.com/s/photos/knit-cardigan-flat-lay?license=free"),
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
            page.evaluate("window.scrollBy(0, 1000)")
            page.wait_for_timeout(1500)

            items = page.evaluate("""() => {
                const imgs = Array.from(document.querySelectorAll('figure img'));
                const list = [];
                for (const img of imgs) {
                    const src = img.src || '';
                    const srcset = img.srcset || '';
                    const full = src + ' ' + srcset;
                    if (full.includes('plus.unsplash.com')) continue;
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
            scraped[name] = items[:12]
        except Exception as e:
            print(f"  Error on {name}: {e}")

    browser.close()

with open("targeted_unsplash.json", "w", encoding="utf-8") as f:
    json.dump(scraped, f, indent=2)

print("Scrape complete! Downloading candidates...")

for cat, items in scraped.items():
    for idx, it in enumerate(items[:6]):
        pid = it["pid"]
        alt = it["alt"]
        fname = f"targeted_curated/{cat}_{idx}_{pid}.jpg"
        if not os.path.exists(fname):
            url = f"https://images.unsplash.com/photo-{pid}?auto=format&fit=crop&w=600&q=80"
            try:
                req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
                with urllib.request.urlopen(req, context=ctx) as r, open(fname, "wb") as f:
                    f.write(r.read())
                print(f"  Saved {fname} ({alt[:40]})")
            except Exception as e:
                print(f"  Download error {pid}: {e}")
