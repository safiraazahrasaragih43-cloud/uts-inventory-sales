import urllib.request
import ssl
import os

ctx = ssl._create_unverified_context()
os.makedirs("img_candidates", exist_ok=True)

urls = [
    "https://images.unsplash.com/photo-1434389677669-e08b4cda3a76?w=600&q=80",
    "https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1506629082955-511b1aa562c8?w=600&q=80",
    "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600&q=80",
    "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1543512214-318c7553f230?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1546868871-7041f2d55e0f?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80",
    "https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&q=80",
    "https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=600&q=80",
    "https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1581044777550-4cfa60707c03?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&q=80",
    "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80",
    "https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=600&q=80",
    "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=600&q=80"
]

for idx, u in enumerate(urls):
    fname = f"img_candidates/cand_{idx}_{u.split('photo-')[1].split('?')[0]}.jpg"
    try:
        req = urllib.request.Request(u, headers={"User-Agent": "Mozilla/5.0"})
        with urllib.request.urlopen(req, context=ctx) as r, open(fname, "wb") as f:
            f.write(r.read())
        print(f"Downloaded {fname}")
    except Exception as e:
        print(f"Error {u}: {e}")
