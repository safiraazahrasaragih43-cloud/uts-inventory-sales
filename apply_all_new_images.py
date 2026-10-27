import sqlite3
import urllib.request
import ssl
import os

ctx = ssl._create_unverified_context()

mapping = {
    'BLUSH-DRS-001': 'https://images.unsplash.com/photo-1572005700111-a96a2cb503c2?auto=format&fit=crop&w=800&q=80',
    'BLUSH-DRS-002': 'https://images.unsplash.com/photo-1640923160720-35dddb6348ab?auto=format&fit=crop&w=800&q=80',
    'BLUSH-TOP-001': 'https://images.unsplash.com/photo-1745381251702-67b07225e516?auto=format&fit=crop&w=800&q=80',
    'BLUSH-TOP-002': 'https://images.unsplash.com/photo-1777462985111-9da64fb2e6e6?auto=format&fit=crop&w=800&q=80',
    'BLUSH-SKT-001': 'https://images.unsplash.com/photo-1766238955752-0200601334ed?auto=format&fit=crop&w=800&q=80',
    'BLUSH-SKT-002': 'https://images.unsplash.com/photo-1687226426353-498d40ec6fbf?auto=format&fit=crop&w=800&q=80',
    'BLUSH-KNT-001': 'https://images.unsplash.com/photo-1683315565563-f72590773805?auto=format&fit=crop&w=800&q=80',
    'BLUSH-KNT-002': 'https://images.unsplash.com/photo-1641642231157-0849081598a2?auto=format&fit=crop&w=800&q=80',
    'BLUSH-HJB-001': 'https://images.unsplash.com/photo-1592867874873-85480a35d2aa?auto=format&fit=crop&w=800&q=80',
    'BLUSH-HJB-002': 'https://images.unsplash.com/photo-1619043599439-9b750b7b2623?auto=format&fit=crop&w=800&q=80',
    'BLUSH-DRS-003': 'https://images.unsplash.com/photo-1783159143477-7c027bf65772?auto=format&fit=crop&w=800&q=80',
    'BLUSH-TOP-003': 'https://images.unsplash.com/photo-1630167146816-e1f4ff99c00c?auto=format&fit=crop&w=800&q=80',
    'BLUSH-TOP-004': 'https://images.unsplash.com/photo-1622738336927-7584b695fc88?auto=format&fit=crop&w=800&q=80',
}

# Update SQLite Database
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

os.makedirs('storage/app/public/products', exist_ok=True)

print("Updating database and local assets...")
for sku, url in mapping.items():
    cursor.execute("UPDATE products SET image = ? WHERE sku = ?", (url, sku))
    print(f"Updated DB for {sku} -> {url[:60]}...")
    
    # Download local file copy
    fname = f"storage/app/public/products/{sku.lower()}.jpg"
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, context=ctx) as r, open(fname, 'wb') as f:
            f.write(r.read())
        print(f"  Downloaded to {fname} ({os.path.getsize(fname)} bytes)")
    except Exception as e:
        print(f"  Error downloading local copy for {sku}: {e}")

conn.commit()
conn.close()

print("\nDatabase update and local caching complete!")
