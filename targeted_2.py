from playwright.sync_api import sync_playwright
import json
import urllib.request
import ssl
import os

ctx = ssl._create_unverified_context()
os.makedirs("targeted_2", exist_ok=True)

queries = [
    ("skirt_pleat", "https://unsplash.com/s/photos/pleated-skirt?license=free"),
    ("skirt_flatlay", "https://unsplash.com/s/photos/skirt-flat-lay?license=free"),
    ("scarf_flatlay", "https://unsplash.com/s/photos/scarf-flat-lay?license=free"),
    ("cardigan_flat", "https://unsplash.com/s/photos/cardigan-flat-lay?license=free"),
    ("hijab_flat", "https://unsplash.com/s/photos/hijab?license=free"),
    ("dress_boutique", "https://unsplash.com/s/photos/boutique-dress?license=free"),
]

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    page = browser.new_page()

    for name, url in queries:
        print(f"Searching {name}: {url}")
        try:
            page.goto(url, wait_until="domcontentloaded", timeout=20000)
            page.wait_for_timeout(3000)
            page.evaluate("window.scrollBy(0, 1500)")
            page.wait_for_timeout(2000)

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
            print(f"  Found {len(items)} items")
            for idx, it in enumerate(items[:8]):
                pid = it["pid"]
                alt = it["alt"]
                fname = f"targeted_2/{name}_{idx}_{pid}.jpg"
                if not os.path.exists(fname):
                    img_u = f"https://images.unsplash.com/photo-{pid}?auto=format&fit=crop&w=600&q=80"
                    try:
                        req = urllib.request.Request(img_u, headers={"User-Agent": "Mozilla/5.0"})
                        with urllib.request.urlopen(req, context=ctx) as r, open(fname, "wb") as out:
                            out.write(r.read())
                        print(f"    Saved {fname} ({alt[:30]})")
                    except Exception as e:
                        pass
        except Exception as e:
            print(f"  Error on {name}: {e}")

    browser.close()

print("Finished targeted_2!")
