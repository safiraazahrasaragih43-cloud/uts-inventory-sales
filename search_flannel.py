import asyncio
import urllib.parse
from playwright.async_api import async_playwright

QUERIES = [
    'plaid flannel shirt flat lay',
    'flannel shirt on wooden hanger',
    'red plaid shirt flat lay',
    'oversized plaid shirt flatlay',
    'tartan flannel shirt flat lay'
]

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=False)
        page = await browser.new_page()
        for q in QUERIES:
            url = f"https://unsplash.com/s/photos/{urllib.parse.quote(q)}"
            try:
                await page.goto(url, timeout=25000)
                await asyncio.sleep(2)
                imgs = await page.eval_on_selector_all(
                    'figure a[href^="/photos/"] img',
                    '''elements => elements.map(e => ({
                        src: e.src,
                        alt: e.alt
                    }))'''
                )
                print(f"=== {q} ({len(imgs)}) ===", flush=True)
                for it in imgs[:6]:
                    print(" ", it['alt'], "->", it['src'].split('?')[0], flush=True)
            except Exception as e:
                print("Error:", e, flush=True)
        await browser.close()

if __name__ == '__main__':
    asyncio.run(run())
