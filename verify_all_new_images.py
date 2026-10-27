from playwright.sync_api import sync_playwright
import os

os.makedirs('browser_snapshots/new_images', exist_ok=True)

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page(viewport={'width': 1400, 'height': 2400})
    
    print("Navigating to http://127.0.0.1:8000...")
    page.goto('http://127.0.0.1:8000', wait_until='networkidle')
    page.wait_for_timeout(2000)
    
    # Check all images on the page
    product_images = page.evaluate('''() => {
        const cards = Array.from(document.querySelectorAll('.group.bg-white'));
        return cards.map(c => {
            const titleEl = c.querySelector('h3');
            const skuEl = c.querySelector('.tracking-widest') || c.querySelector('.font-mono') || c.querySelector('[class*="BLUSH"]');
            const imgEl = c.querySelector('img');
            return {
                title: titleEl ? titleEl.innerText.trim() : 'Unknown',
                imgSrc: imgEl ? imgEl.src : null,
                complete: imgEl ? imgEl.complete : false,
                naturalWidth: imgEl ? imgEl.naturalWidth : 0,
                naturalHeight: imgEl ? imgEl.naturalHeight : 0,
            };
        });
    }''')
    
    print(f"Found {len(product_images)} product cards:")
    all_ok = True
    for p_info in product_images:
        ok = p_info['complete'] and p_info['naturalWidth'] > 0
        if not ok:
            all_ok = False
        status = "[OK]" if ok else "[FAILED]"
        src_display = (p_info['imgSrc'][:60] + '...') if p_info['imgSrc'] else 'No imgSrc'
        print(f"  {status} {p_info['title']} -> {p_info['naturalWidth']}x{p_info['naturalHeight']} ({src_display})")
    
    # Take screenshot of the catalog grid
    page.screenshot(path='browser_snapshots/new_images/catalog_full.png', full_page=True)
    
    # Also take focused screenshot of top cards
    cards = page.locator('.group.bg-white')
    if cards.count() >= 3:
        page.locator('#koleksi').screenshot(path='browser_snapshots/new_images/catalog_section.png')
    
    browser.close()
    
    if all_ok:
        print("\nAll product images successfully loaded with positive natural dimensions!")
    else:
        print("\nSome images failed to load!")
