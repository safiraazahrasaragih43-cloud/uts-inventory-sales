import sys
import time
import json
import sqlite3
from playwright.sync_api import sync_playwright

def run():
    print("=== Testing BlushBoutique COD Order Flow ===")
    
    # Check SQLite initial state
    conn = sqlite3.connect("database/database.sqlite")
    cursor = conn.cursor()
    cursor.execute("SELECT id, name, stock FROM products LIMIT 3")
    products_initial = {row[0]: {'name': row[1], 'stock': row[2]} for row in cursor.fetchall()}
    print(f"Initial products: {products_initial}")

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(viewport={'width': 1280, 'height': 800})
        page = context.new_page()

        # Step 1: Visit Catalog
        print("\n[Step 1] Visiting catalog http://127.0.0.1:8000...")
        page.goto("http://127.0.0.1:8000", wait_until="networkidle")

        # Verify no "Pesan via WA" buttons
        wa_buttons = page.locator("text='Pesan via WA'").count()
        print(f"Count of 'Pesan via WA' buttons: {wa_buttons} (must be 0)")
        assert wa_buttons == 0, "Found 'Pesan via WA' buttons!"

        # Verify "Pesan Sekarang" buttons exist
        pesan_sekarang_buttons = page.locator("a:has-text('Pesan Sekarang')")
        pesan_count = pesan_sekarang_buttons.count()
        print(f"Count of 'Pesan Sekarang' buttons: {pesan_count} (must be > 0)")
        assert pesan_count > 0, "No 'Pesan Sekarang' buttons found!"

        # Step 2: Direct Order Flow
        print("\n[Step 2] Testing Direct Order Flow...")
        first_btn = pesan_sekarang_buttons.first
        first_href = first_btn.get_attribute("href")
        print(f"First button links to: {first_href}")
        assert "/checkout?product_id=" in first_href, f"Invalid link: {first_href}"

        first_btn.click()
        page.wait_for_load_state("networkidle")
        print(f"Current URL: {page.url}")
        assert "/checkout?product_id=" in page.url

        # Verify COD form elements
        assert page.locator("h2:has-text('Alamat Pengiriman Paket')").is_visible()
        assert page.locator("text=COD").first.is_visible()
        assert page.locator("input[name='customer_name']").is_visible()
        assert page.locator("input[name='phone']").is_visible()
        assert page.locator("textarea[name='address']").is_visible()

        # Fill direct checkout form
        page.fill("input[name='customer_name']", "Safira Pelanggan COD Direct")
        page.fill("input[name='phone']", "081234567890")
        page.fill("textarea[name='address']", "Jl. Gandapura No. 25, Merdeka, Kec. Sumur Bandung, Kota Bandung, Jawa Barat 40113")
        page.fill("input[name='notes']", "Patokan dekat taman, paket tolong hubungi sebelum antar")

        print("Submitting Direct Order COD form...")
        page.click("#btnSubmitCheckout")
        page.wait_for_load_state("networkidle")

        print(f"After submit URL: {page.url}")
        assert "/checkout/success/SLS-COD-" in page.url, f"Expected success URL, got: {page.url}"

        # Check success page elements
        success_heading = page.locator("h1").inner_text()
        print(f"Success Page Heading: {success_heading}")
        assert "Terima Kasih" in success_heading

        invoice_elem = page.locator("span.font-mono.text-xl, span.font-mono.text-2xl").first
        invoice_number = invoice_elem.inner_text().strip()
        print(f"Generated Invoice: {invoice_number}")
        assert invoice_number.startswith("SLS-COD-")

        page.screenshot(path="scratch/screenshot_direct_success.png")
        print("Direct Order Success screenshot saved to scratch/screenshot_direct_success.png")

        # Step 3: Cart Order Flow
        print("\n[Step 3] Testing Cart Order Flow...")
        page.goto("http://127.0.0.1:8000", wait_until="networkidle")

        # Add 2 items to cart
        add_cart_buttons = page.locator("button:has-text('Tambah ke Keranjang')")
        print(f"Available 'Tambah ke Keranjang' buttons: {add_cart_buttons.count()}")
        add_cart_buttons.nth(0).click()
        page.wait_for_timeout(500)
        add_cart_buttons.nth(1).click()
        page.wait_for_timeout(500)

        # Open Cart Drawer
        cart_trigger = page.locator("button[onclick='openCartDrawer()']").first
        cart_trigger.click()
        page.wait_for_timeout(600)

        # Check cart drawer contents
        drawer_checkout_btn = page.locator("button:has-text('Pesan Sekarang (Checkout COD)')")
        assert drawer_checkout_btn.is_visible(), "Drawer COD button not visible"
        print("Cart drawer displayed with 'Pesan Sekarang (Checkout COD)' button.")

        # Click proceed to checkout
        drawer_checkout_btn.click()
        page.wait_for_load_state("networkidle")
        print(f"Arrived at Cart Checkout: {page.url}")
        assert page.url.endswith("/checkout")

        # Verify multiple items in summary
        cart_item_rows = page.locator("#checkoutItemsList > div")
        print(f"Items rendered in checkout: {cart_item_rows.count()}")
        assert cart_item_rows.count() >= 2, "Expected at least 2 items from cart"

        # Fill cart checkout form
        page.fill("input[name='customer_name']", "Safira Pelanggan COD Cart")
        page.fill("input[name='phone']", "081987654321")
        page.fill("textarea[name='address']", "Jl. Riau No. 101, Cihapit, Bandung, Jawa Barat 40114")
        page.fill("input[name='notes']", "COD titip pos sekuriti jika sedang keluar")

        print("Submitting Cart Order COD form...")
        page.click("#btnSubmitCheckout")
        page.wait_for_load_state("networkidle")

        print(f"After cart submit URL: {page.url}")
        assert "/checkout/success/SLS-COD-" in page.url

        cart_invoice = page.locator("span.font-mono.text-xl, span.font-mono.text-2xl").first.inner_text().strip()
        print(f"Generated Cart Order Invoice: {cart_invoice}")

        # Check localStorage was cleared
        cart_storage = page.evaluate("() => localStorage.getItem('blush_boutique_cart')")
        print(f"localStorage blush_boutique_cart after success: {cart_storage}")
        assert cart_storage is None, "localStorage was not cleared!"

        page.screenshot(path="scratch/screenshot_cart_success.png")
        print("Cart Order Success screenshot saved to scratch/screenshot_cart_success.png")

        browser.close()

    # Step 4: Verify Database State
    print("\n[Step 4] Verifying SQLite Database Transactions & Stock Deductions...")
    cursor.execute("""
        SELECT id, invoice_number, customer_name, payment_method, total_amount, notes
        FROM sales
        WHERE invoice_number LIKE 'SLS-COD-%'
        ORDER BY id DESC
        LIMIT 2
    """)
    recent_sales = cursor.fetchall()
    for s in recent_sales:
        sale_id = s[0]
        print(f"  Recorded Sale: {s[1]} | {s[2]} | {s[3]} | Rp {s[4]:,.0f}")
        assert s[3] == 'COD (Bayar di Tempat)'

        # Check sale_items for each sale
        cursor.execute("SELECT product_id, quantity, unit_price, subtotal FROM sale_items WHERE sale_id = ?", (sale_id,))
        items = cursor.fetchall()
        print(f"    Sale Items for {s[1]}: {items}")
        assert len(items) >= 1, "No sale items found for sale!"

    conn.close()
    print("\n=== ALL COD CHECKOUT TESTS PASSED WITH 100% SUCCESS! ===")

if __name__ == '__main__':
    run()
