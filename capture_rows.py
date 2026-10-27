from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page(viewport={'width': 1280, 'height': 800})
    page.goto('http://127.0.0.1:8000', wait_until='networkidle')
    page.wait_for_timeout(2000)
    
    # Scroll to catalog
    page.evaluate('window.scrollTo(0, 500)')
    page.wait_for_timeout(1000)
    page.screenshot(path='browser_snapshots/new_images/grid_row_1.png')
    
    page.evaluate('window.scrollTo(0, 1150)')
    page.wait_for_timeout(1000)
    page.screenshot(path='browser_snapshots/new_images/grid_row_2.png')
    
    page.evaluate('window.scrollTo(0, 1800)')
    page.wait_for_timeout(1000)
    page.screenshot(path='browser_snapshots/new_images/grid_row_3.png')
    
    page.evaluate('window.scrollTo(0, 2450)')
    page.wait_for_timeout(1000)
    page.screenshot(path='browser_snapshots/new_images/grid_row_4.png')
    
    browser.close()
    print("Screenshots taken!")
