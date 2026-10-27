import asyncio
import json
import urllib.parse
from playwright.async_api import async_playwright

SEARCH_TASKS = {
    'prod_1_rose_floral_dress': 'floral midi dress puff sleeve',
    'prod_2_dusty_pink_satin_dress': 'pink satin dress elegant',
    'prod_3_peach_ribbon_blouse': 'pastel peach blouse women',
    'prod_4_victorian_lace_shirt': 'victorian lace collar blouse white',
    'prod_5_rose_pleated_skirt': 'pink pleated midi skirt women',
    'prod_6_cream_linen_culottes': 'cream linen wide leg pants women',
    'prod_7_lavender_cardigan': 'lavender knit cardigan buttons',
    'prod_8_pink_cable_knit': 'pink cable knit sweater women',
    'prod_9_voile_square_hijab': 'pink square hijab portrait',
    'prod_10_satin_silk_pashmina': 'silk satin scarf folded rose',
    'prod_11_maroon_silk_dress': 'maroon silk evening gown',
    'prod_12_flannel_plaid_shirt': 'plaid flannel shirt women oversized',
    'prod_13_peony_silk_blouse': 'floral silk blouse women pastel'
}

async def fetch_candidates():
    results = {}
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=False)
        context = await browser.new_context()
        page = await context.new_page()

        for key, query in SEARCH_TASKS.items():
            print(f"Searching for {key}: '{query}'...")
            url = f"https://unsplash.com/s/photos/{urllib.parse.quote(query)}"
            try:
                await page.goto(url, timeout=30000)
                await asyncio.sleep(3.5)
                # extract images from photo grid
                # In Unsplash, photo links are in a[href^="/photos/"] or figure img
                imgs = await page.eval_on_selector_all(
                    'figure a[href^="/photos/"] img',
                    '''elements => elements.map(e => ({
                        src: e.src,
                        alt: e.alt,
                        srcset: e.srcset
                    }))'''
                )
                if not imgs:
                    imgs = await page.eval_on_selector_all(
                        'figure img',
                        '''elements => elements.map(e => ({
                            src: e.src,
                            alt: e.alt
                        }))'''
                    )

                valid_imgs = [im for im in imgs if im.get('src') and 'profile' not in im['src']]
                results[key] = valid_imgs[:8]
                print(f"  -> Found {len(valid_imgs)} candidate images")
            except Exception as e:
                print(f"  Error on {key}: {e}")
                results[key] = []

        await browser.close()

    with open('candidates.json', 'w', encoding='utf-8') as f:
        json.dump(results, f, indent=2, ensure_ascii=False)
    print("Done! Saved to candidates.json")

if __name__ == '__main__':
    asyncio.run(fetch_candidates())
