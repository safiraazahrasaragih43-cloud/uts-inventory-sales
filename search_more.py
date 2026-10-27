import asyncio
import json
import urllib.parse
from playwright.async_api import async_playwright

QUERIES = [
    ('hijab_1', 'hijab fashion portrait aesthetic'),
    ('hijab_2', 'hijabi model aesthetic'),
    ('hijab_3', 'square hijab scarf'),
    ('pashmina_1', 'silk scarf aesthetic drape'),
    ('pashmina_2', 'pink silk scarf folded'),
    ('pashmina_3', 'champagne silk fabric scarf'),
    ('cardigan_1', 'knit cardigan buttons aesthetic'),
    ('cardigan_2', 'lavender cardigan knitwear'),
    ('cardigan_3', 'pastel knit cardigan aesthetic'),
    ('sweater_1', 'pink cable knit sweater'),
    ('sweater_2', 'cable knit sweater aesthetic pink'),
    ('skirt_1', 'pink pleated skirt aesthetic'),
    ('skirt_2', 'pleated a line midi skirt'),
    ('culottes_1', 'beige linen pants wide leg aesthetic'),
    ('culottes_2', 'cream linen trousers women aesthetic'),
    ('dress_1', 'rose floral midi dress puff sleeve vintage'),
    ('dress_2', 'floral puff sleeve midi dress women'),
    ('dress_3', 'pink satin wrap dress aesthetic'),
    ('dress_4', 'maroon silk evening dress gown'),
    ('dress_5', 'burgundy satin dress elegant'),
    ('blouse_1', 'peach blouse bow collar aesthetic'),
    ('blouse_2', 'pastel pink blouse feminine aesthetic'),
    ('blouse_3', 'victorian lace collar blouse white aesthetic'),
    ('blouse_4', 'white linen lace blouse women'),
    ('flannel_1', 'plaid flannel shirt women aesthetic'),
    ('flannel_2', 'oversized plaid flannel shirt pastel aesthetic'),
    ('blouse_5', 'floral silk blouse women pastel aesthetic')
]

async def run():
    all_results = {}
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=False)
        context = await browser.new_context()
        page = await context.new_page()

        for tag, q in QUERIES:
            url = f"https://unsplash.com/s/photos/{urllib.parse.quote(q)}"
            try:
                await page.goto(url, timeout=30000)
                await asyncio.sleep(2.5)
                imgs = await page.eval_on_selector_all(
                    'figure a[href^="/photos/"] img',
                    '''elements => elements.map(e => ({
                        src: e.src,
                        alt: e.alt
                    }))'''
                )
                filtered = [im for im in imgs if im.get('src') and 'profile' not in im['src']]
                all_results[tag] = filtered[:6]
                print(f"[{tag}] '{q}' -> {len(filtered)} images")
            except Exception as e:
                print(f"[{tag}] Error: {e}")
                all_results[tag] = []

        await browser.close()

    with open('deep_candidates.json', 'w', encoding='utf-8') as f:
        json.dump(all_results, f, indent=2, ensure_ascii=False)
    print("Done!")

if __name__ == '__main__':
    asyncio.run(run())
