from playwright.sync_api import sync_playwright
import json

queries = [
    "dress hanger flat lay",
    "pink satin dress flat lay",
    "pastel blouse flat lay",
    "lace collar blouse flat lay",
    "pleated skirt flat lay",
    "linen culottes flat lay",
    "pastel cardigan flat lay",
    "knit sweater folded flat lay",
    "silk hijab folded flat lay",
    "pashmina shawl flat lay",
    "maroon dress flat lay hanger",
    "plaid shirt hanger flat lay",
    "floral blouse flat lay"
]

results = {}

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page()
    for q in queries:
        url = f"https://unsplash.com/s/photos/{q.replace(' ', '-')}"
        print(f"Fetching: {url}")
        try:
            page.goto(url, wait_until="domcontentloaded", timeout=20000)
            page.wait_for_timeout(2500)
            imgs = page.evaluate("""() => {
                const elements = Array.from(document.querySelectorAll('img[src*="images.unsplash.com/photo-"]'));
                return elements.map(e => ({
                    alt: e.alt || '',
                    src: e.src
                })).filter(x => x.alt.length > 5);
            }""")
            results[q] = imgs[:8]
            print(f"  Found {len(imgs)} images")
        except Exception as e:
            print(f"  Error: {e}")
    browser.close()

with open("unsplash_candidates.json", "w", encoding="utf-8") as f:
    json.dump(results, f, indent=2)

print("Saved to unsplash_candidates.json")
