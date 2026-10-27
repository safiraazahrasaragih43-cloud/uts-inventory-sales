import asyncio
import json
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=False)
        page = await browser.new_page()
        await page.goto("https://unsplash.com/s/photos/pink-pleated-skirt", timeout=30000)
        await asyncio.sleep(2.5)
        imgs = await page.eval_on_selector_all(
            'figure a[href^="/photos/"] img',
            '''elements => elements.map(e => ({
                src: e.src,
                alt: e.alt
            }))'''
        )
        print(f"Found {len(imgs)} photos:")
        for im in imgs:
            clean = im['src'].split('?')[0] + "?auto=format&fit=crop&w=800&q=80"
            if 'images.unsplash.com/photo-' in clean:
                print(f"  {im['alt']} -> {clean}")

        with open('pink_skirt_photos.json', 'w', encoding='utf-8') as f:
            json.dump(imgs, f, indent=2)
        await browser.close()

if __name__ == '__main__':
    asyncio.run(run())
