from playwright.sync_api import sync_playwright
import urllib.request
import ssl
import os

ctx = ssl._create_unverified_context()
os.makedirs("scarf_test", exist_ok=True)

queries = [
    ("silk_scarf", "https://unsplash.com/s/photos/silk-scarf?license=free"),
    ("satin_scarf", "https://unsplash.com/s/photos/satin-scarf?license=free"),
    ("hijab_display", "https://unsplash.com/s/photos/scarf-display?license=free"),
]

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    page = browser.new_page()

    for name, url in queries:
        try:
            page.goto(url, wait_until="domcontentloaded", timeout=20000)
            page.wait_for_timeout(3000)
            page.evaluate("window.scrollBy(0, 1000)")
            page.wait_for_timeout(1500)

            items = page.evaluate("""() => {
                const imgs = Array.from(document.querySelectorAll('figure img'));
                const list = [];
                for (const img of imgs) {
                    const full = (img.src || '') + ' ' + (img.srcset || '');
                    if (full.includes('plus.unsplash.com')) continue;
                    const m = full.match(/images\.unsplash\.com\/photo-([0-9a-zA-Z_-]+)/);
                    if (m) {
                        list.push({ pid: m[1], alt: img.alt || '' });
                    }
                }
                return list;
            }""")
            print(f"{name}: found {len(items)}")
            for idx, it in enumerate(items[:6]):
                pid = it["pid"]
                alt = it["alt"]
                fname = f"scarf_test/{name}_{idx}_{pid}.jpg"
                if not os.path.exists(fname):
                    img_u = f"https://images.unsplash.com/photo-{pid}?auto=format&fit=crop&w=600&q=80"
                    try:
                        req = urllib.request.Request(img_u, headers={"User-Agent": "Mozilla/5.0"})
                        with urllib.request.urlopen(req, context=ctx) as r, open(fname, "wb") as out:
                            out.write(r.read())
                        print(f"  Saved {fname} ({alt[:30]})")
                    except Exception as e:
                        pass
        except Exception as e:
            print(f"Error {name}: {e}")

    browser.close()
