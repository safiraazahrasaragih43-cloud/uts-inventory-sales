import asyncio
import json
import urllib.parse
from playwright.async_api import async_playwright

SEARCHES = [
    ('red_dress_white_bg', 'red evening dress white background'),
    ('maroon_dress_hanger', 'maroon dress on clothes hanger'),
    ('red_gown_hanger', 'red gown on hanger'),
    ('maroon_silk_gown', 'burgundy evening gown hanger'),
    ('flannel_white_bg', 'plaid shirt white background'),
    ('flannel_flat_lay_clean', 'flannel shirt flat lay white background'),
    ('plaid_shirt_flat_lay', 'plaid shirt flat lay aesthetic'),
    ('floral_blouse_clean', 'floral blouse white background')
]

async def run():
    results = {}
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=False)
        context = await browser.new_context()
        page = await context.new_page()

        for tag, q in SEARCHES:
            url = f"https://unsplash.com/s/photos/{urllib.parse.quote(q)}"
            try:
                await page.goto(url, timeout=30000)
                await asyncio.sleep(2)
                imgs = await page.eval_on_selector_all(
                    'figure a[href^="/photos/"] img',
                    '''elements => elements.map(e => ({
                        src: e.src,
                        alt: e.alt
                    }))'''
                )
                filtered = [im for im in imgs if im.get('src') and 'profile' not in im['src']]
                results[tag] = filtered[:12]
                print(f"[{tag}] '{q}' -> {len(filtered)} results")
            except Exception as e:
                print(f"[{tag}] Error: {e}")

        await browser.close()

    with open('apparel_candidates.json', 'w', encoding='utf-8') as f:
        json.dump(results, f, indent=2, ensure_ascii=False)

if __name__ == '__main__':
    asyncio.run(run())
