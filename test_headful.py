from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    page = browser.new_page()
    page.goto('https://unsplash.com/photos/a-wedding-dress-hanging-on-a-wooden-hanger-aJoQ4VGlIys')
    page.wait_for_timeout(3000)
    img_url = page.evaluate('''() => {
        const og = document.querySelector('meta[property="og:image"]');
        return og ? og.content : '';
    }''')
    print('OG Image URL:', img_url)
    browser.close()
