from playwright.sync_api import sync_playwright
import json
import time

search_terms = {
    "dress_floral": "floral dress flat lay",
    "dress_dusty": "pink dress flat lay",
    "dress_maroon": "maroon dress hanger",
    "top_lace": "lace blouse flat lay",
    "skirt_pleated": "pleated skirt flat lay",
    "culottes_linen": "linen pants flat lay",
    "cardigan_knit": "cardigan flat lay",
    "sweater_knit": "knit sweater flat lay folded",
    "hijab_square": "silk scarf flat lay",
    "pashmina_silk": "cashmere shawl flat lay"
}

results = {}

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    context = browser.new_context()
    page = context.new_page()

    for key, term in search_terms.items():
        slug = term.replace(" ", "-")
        url = f"https://unsplash.com/s/photos/{slug}"
        print(f"Navigating to {url}...")
        try:
            page.goto(url, wait_until="domcontentloaded", timeout=25000)
            page.wait_for_timeout(3500)
            # Scroll a bit
            page.evaluate("window.scrollBy(0, 800)")
            page.wait_for_timeout(1500)

            imgs = page.evaluate("""() => {
                const elements = Array.from(document.querySelectorAll('figure img'));
                return elements.map(el => {
                    const src = el.src || '';
                    const srcset = el.srcset || '';
                    const alt = el.alt || '';
                    // Extract photo ID
                    const match = (src + ' ' + srcset).match(/photo-([0-9a-zA-Z_-]+)/);
                    return {
                        alt: alt,
                        photo_id: match ? match[1] : null,
                        src: src
                    };
                }).filter(x => x.photo_id && x.alt && x.alt.length > 5);
            }""")
            results[key] = imgs[:10]
            print(f"  Found {len(imgs)} photos for {key}")
        except Exception as e:
            print(f"  Error on {key}: {e}")

    browser.close()

with open("unsplash_scraped.json", "w", encoding="utf-8") as f:
    json.dump(results, f, indent=2)

print("Done! Saved to unsplash_scraped.json")
