import urllib.request
import ssl
import os

ctx = ssl._create_unverified_context()
os.makedirs("img_audit", exist_ok=True)

images = {
    "DRS-001": "https://images.unsplash.com/photo-1760172551807-018cc883cff8?auto=format&fit=crop&w=800&q=80",
    "DRS-002": "https://images.unsplash.com/photo-1731335391054-bcb9df422982?auto=format&fit=crop&w=800&q=80",
    "TOP-001": "https://images.unsplash.com/photo-1745381251702-67b07225e516?auto=format&fit=crop&w=800&q=80",
    "TOP-002": "https://images.unsplash.com/photo-1772915516567-c1065e48d8cd?auto=format&fit=crop&w=800&q=80",
    "SKT-001": "https://images.unsplash.com/photo-1583235340050-f73f853f1b0f?auto=format&fit=crop&w=800&q=80",
    "SKT-002": "https://images.unsplash.com/photo-1789110853687-71692f0e86be?auto=format&fit=crop&w=800&q=80",
    "KNT-001": "https://images.unsplash.com/photo-1687275164205-732738f0c7bc?auto=format&fit=crop&w=800&q=80",
    "KNT-002": "https://images.unsplash.com/photo-1687275166296-7766371fd254?auto=format&fit=crop&w=800&q=80",
    "HJB-001": "https://images.unsplash.com/photo-1626497361649-81cc097e9bfd?auto=format&fit=crop&w=800&q=80",
    "HJB-002": "https://images.unsplash.com/photo-1619043599439-9b750b7b2623?auto=format&fit=crop&w=800&q=80",
    "DRS-003": "https://images.pexels.com/photos/19895956/pexels-photo-19895956.jpeg?auto=compress&cs=tinysrgb&w=800",
    "TOP-003": "https://images.unsplash.com/photo-1630167146816-e1f4ff99c00c?auto=format&fit=crop&w=800&q=80",
    "TOP-004": "https://images.unsplash.com/photo-1622738336927-7584b695fc88?auto=format&fit=crop&w=800&q=80",
}

for k, u in images.items():
    path = f"img_audit/{k}.jpg"
    try:
        req = urllib.request.Request(u, headers={"User-Agent": "Mozilla/5.0"})
        with urllib.request.urlopen(req, context=ctx) as r, open(path, "wb") as f:
            f.write(r.read())
        print(f"Downloaded {k} ({os.path.getsize(path)} bytes)")
    except Exception as e:
        print(f"Error {k}: {e}")
