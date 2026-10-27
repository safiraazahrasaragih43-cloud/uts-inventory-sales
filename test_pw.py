import asyncio
from playwright.async_api import async_playwright

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36"
        )
        page = await context.new_page()
        # Test Unsplash
        print("Visiting unsplash...")
        await page.goto("https://unsplash.com/s/photos/hijab", wait_until="networkidle", timeout=30000)
        title = await page.title()
        print("Page title:", title)
        imgs = await page.eval_on_selector_all('figure img', 'elements => elements.map(e => ({src: e.src, alt: e.alt}))')
        print(f"Found {len(imgs)} images:")
        for im in imgs[:5]:
            print(" ", im)
        await browser.close()

asyncio.run(main())
