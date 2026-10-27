import asyncio
import os
from playwright.async_api import async_playwright

CATEGORIES = [
    ('', 'all_catalog'),
    ('?category=premium-scarves-hijabs', 'hijabs'),
    ('?category=knitwear-cardigans', 'cardigans'),
    ('?category=pleated-skirts-culottes', 'skirts_culottes'),
    ('?category=pastel-blouses-tops', 'blouses'),
    ('?category=floral-midi-dresses', 'dresses'),
]

async def verify():
    os.makedirs('browser_snapshots', exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page(viewport={'width': 1280, 'height': 900})

        for query, label in CATEGORIES:
            url = f"http://127.0.0.1:8000/{query}"
            print(f"\n--- Checking {url} ({label}) ---")
            response = await page.goto(url, wait_until="networkidle", timeout=30000)
            print(f"Status: {response.status}")
            
            # Scroll down to trigger any lazy loaded images
            await page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
            await asyncio.sleep(2)
            await page.evaluate("window.scrollTo(0, 0)")
            await asyncio.sleep(1)

            # Evaluate product cards and their images
            card_info = await page.evaluate('''() => {
                const cards = document.querySelectorAll('.product-item-card');
                return Array.from(cards).map(c => {
                    const name = c.querySelector('h3') ? c.querySelector('h3').innerText.trim() : '';
                    const img = c.querySelector('.fashion-img');
                    return {
                        name: name,
                        imgSrc: img ? img.currentSrc || img.src : '',
                        complete: img ? img.complete : false,
                        naturalWidth: img ? img.naturalWidth : 0,
                        naturalHeight: img ? img.naturalHeight : 0,
                    };
                });
            }''')

            print(f"Products found on page: {len(card_info)}")
            for p_info in card_info:
                ok = p_info['complete'] and p_info['naturalWidth'] > 0
                status_symbol = "OK" if ok else "FAIL_BROKEN"
                print(f"  [{status_symbol}] {p_info['name']}")
                print(f"      Size: {p_info['naturalWidth']}x{p_info['naturalHeight']} | URL: {p_info['imgSrc'][:80]}...")

            # Take snapshot screenshot (full page to view all cards)
            screenshot_path = f"browser_snapshots/{label}.png"
            await page.screenshot(path=screenshot_path, full_page=True)
            print(f"Saved screenshot to {screenshot_path}")

        # Also check product detail page for Hijab: Product ID 9
        print("\n--- Checking Product Show (Hijab: ID 9) ---")
        await page.goto("http://127.0.0.1:8000/catalog/9", wait_until="networkidle", timeout=30000)
        await asyncio.sleep(2)
        show_img = await page.evaluate('''() => {
            const img = document.querySelector('img.object-cover');
            return {
                src: img ? img.currentSrc || img.src : '',
                complete: img ? img.complete : false,
                naturalWidth: img ? img.naturalWidth : 0
            };
        }''')
        print(f"Product 9 Detail Image: {show_img}")
        await page.screenshot(path="browser_snapshots/show_product_9.png", full_page=True)

        # Also check Dashboard
        print("\n--- Checking Dashboard ---")
        await page.goto("http://127.0.0.1:8000/dashboard", wait_until="networkidle", timeout=30000)
        # Check if redirected to login
        current_url = page.url
        print(f"Dashboard URL / redirect: {current_url}")
        if 'login' in current_url:
            print("Logging in to check dashboard...")
            await page.fill('input[name="email"]', 'admin@blushboutique.com')
            await page.fill('input[name="password"]', 'password123')
            await page.click('button[type="submit"]')
            await page.wait_for_load_state("networkidle")
            print(f"After login URL: {page.url}")
            await asyncio.sleep(2)
            await page.screenshot(path="browser_snapshots/dashboard.png", full_page=True)
            print("Saved dashboard screenshot")

        await browser.close()
    print("\nAll browser verifications finished successfully!")

if __name__ == '__main__':
    asyncio.run(verify())
