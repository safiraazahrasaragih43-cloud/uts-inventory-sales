from playwright.sync_api import sync_playwright
import json

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    page = browser.new_page()
    page.goto('https://unsplash.com/photos/1687275168013-dcc11d9c74ab')
    page.wait_for_timeout(3000)
    page.evaluate('window.scrollBy(0, 1000)')
    page.wait_for_timeout(2000)
    
    # Extract user profile link and related photos
    info = page.evaluate('''() => {
        const userLink = document.querySelector('a[href^="/@"]');
        const user = userLink ? userLink.getAttribute('href') : null;
        
        const imgs = Array.from(document.querySelectorAll('figure img')).map(i => ({
            alt: i.alt || '',
            src: i.src || ''
        }));
        return { user, imgs };
    }''')
    print('User:', info['user'])
    print(f'Found {len(info["imgs"])} related images')
    for im in info['imgs'][:15]:
        print(' -', im['alt'][:50], '-->', im['src'].split('?')[0])
    
    # If user exists, let's navigate to user's profile
    if info['user']:
        page.goto('https://unsplash.com' + info['user'])
        page.wait_for_timeout(3000)
        user_imgs = page.evaluate('''() => {
            return Array.from(document.querySelectorAll('figure img')).map(i => ({
                alt: i.alt || '',
                src: i.src || ''
            }));
        }''')
        print(f'User photos: {len(user_imgs)}')
        for im in user_imgs:
            print('  User photo:', im['alt'][:50], '-->', im['src'].split('?')[0])
            
    browser.close()
