from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page(viewport={'width': 1280, 'height': 800})
    page.goto('http://127.0.0.1:8000', wait_until='networkidle')
    page.wait_for_timeout(2000)
    
    page.evaluate('window.scrollTo(0, 3100)')
    page.wait_for_timeout(1000)
    page.screenshot(path='browser_snapshots/new_images/grid_row_5.png')
    
    page.evaluate('window.scrollTo(0, 3750)')
    page.wait_for_timeout(1000)
    page.screenshot(path='browser_snapshots/new_images/grid_row_6.png')
    
    browser.close()
