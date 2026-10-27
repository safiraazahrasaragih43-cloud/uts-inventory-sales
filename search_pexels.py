import asyncio
import urllib.parse
from playwright.async_api import async_playwright

SEARCHES = [
    'plaid shirt flat lay',
    'checkered shirt flat lay',
    'flannel shirt clothing',
    'maroon silk dress flat lay',
    'red dress hanger'
]

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=False)
        page = await browser.new_page()
        for q in SEARCHES:
            url = f"https://www.pexels.com/search/{urllib.parse.quote(q)}/"
            try:
                await page.goto(url, timeout=30000)
                await asyncio.sleep(2.5)
                imgs = await page.eval_on_selector_all(
                    'article img',
                    '''elements => elements.map(e => ({
                        src: e.src,
                        alt: e.alt
                    }))'''
                )
                print(f"=== Pexels: {q} ({len(imgs)}) ===")
                for it in imgs[:6]:
                    print(" ", it['alt'], "->", it['src'])
            except Exception as e:
                print(f"Error on {q}: {e}")
        await browser.close()

if __name__ == '__main__':
    asyncio.run(run())
