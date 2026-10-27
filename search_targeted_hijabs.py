import asyncio
import json
import urllib.parse
from playwright.async_api import async_playwright

SEARCHES = [
    'silk pashmina champagne',
    'satin scarf women model',
    'pashmina silk aesthetic',
    'rose silk scarf model',
    'silk scarf hair tie aesthetic'
]

async def run():
    results = {}
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=False)
        context = await browser.new_context()
        page = await context.new_page()

        for q in SEARCHES:
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
                results[q] = filtered[:8]
                print(f"'{q}' -> {len(filtered)} results")
            except Exception as e:
                print(f"Error on {q}: {e}")

        await browser.close()

    with open('targeted_hijabs.json', 'w', encoding='utf-8') as f:
        json.dump(results, f, indent=2, ensure_ascii=False)

if __name__ == '__main__':
    asyncio.run(run())
