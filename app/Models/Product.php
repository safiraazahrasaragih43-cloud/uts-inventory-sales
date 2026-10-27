<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'purchase_price',
        'selling_price',
        'stock',
        'minimum_stock',
        'image',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function getFormattedSellingPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->selling_price, 0, ',', '.');
    }

    public function getFormattedPurchasePriceAttribute(): string
    {
        return 'Rp ' . number_format($this->purchase_price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
        }

        // High-resolution visual fallback mapping based on SKU or product name
        $skuMap = [
            'BLUSH-DRS-001' => 'https://images.unsplash.com/photo-1572005700111-a96a2cb503c2?auto=format&fit=crop&w=800&q=80',
            'BLUSH-DRS-002' => 'https://images.unsplash.com/photo-1640923160720-35dddb6348ab?auto=format&fit=crop&w=800&q=80',
            'BLUSH-TOP-001' => 'https://images.unsplash.com/photo-1745381251702-67b07225e516?auto=format&fit=crop&w=800&q=80',
            'BLUSH-TOP-002' => 'https://images.unsplash.com/photo-1777462985111-9da64fb2e6e6?auto=format&fit=crop&w=800&q=80',
            'BLUSH-SKT-001' => 'https://images.unsplash.com/photo-1766238955752-0200601334ed?auto=format&fit=crop&w=800&q=80',
            'BLUSH-SKT-002' => 'https://images.unsplash.com/photo-1687226426353-498d40ec6fbf?auto=format&fit=crop&w=800&q=80',
            'BLUSH-KNT-001' => 'https://images.unsplash.com/photo-1683315565563-f72590773805?auto=format&fit=crop&w=800&q=80',
            'BLUSH-KNT-002' => 'https://images.unsplash.com/photo-1641642231157-0849081598a2?auto=format&fit=crop&w=800&q=80',
            'BLUSH-HJB-001' => 'https://images.unsplash.com/photo-1592867874873-85480a35d2aa?auto=format&fit=crop&w=800&q=80',
            'BLUSH-HJB-002' => 'https://images.unsplash.com/photo-1619043599439-9b750b7b2623?auto=format&fit=crop&w=800&q=80',
            'BLUSH-DRS-003' => 'https://images.unsplash.com/photo-1783159143477-7c027bf65772?auto=format&fit=crop&w=800&q=80',
            'BLUSH-TOP-003' => 'https://images.unsplash.com/photo-1630167146816-e1f4ff99c00c?auto=format&fit=crop&w=800&q=80',
            'BLUSH-TOP-004' => 'https://images.unsplash.com/photo-1622738336927-7584b695fc88?auto=format&fit=crop&w=800&q=80',
        ];

        if (!empty($this->sku) && isset($skuMap[$this->sku])) {
            return $skuMap[$this->sku];
        }

        $nameLower = strtolower($this->name);

        if (str_contains($nameLower, 'maroon')) {
            return 'https://images.unsplash.com/photo-1783159143477-7c027bf65772?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'flanel') || str_contains($nameLower, 'plaid')) {
            return 'https://images.unsplash.com/photo-1630167146816-e1f4ff99c00c?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'blus sutra') || (str_contains($nameLower, 'floral') && str_contains($nameLower, 'blus'))) {
            return 'https://images.unsplash.com/photo-1622738336927-7584b695fc88?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'rose floral') || str_contains($nameLower, 'puff midi')) {
            return 'https://images.unsplash.com/photo-1572005700111-a96a2cb503c2?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'satin wrap') || str_contains($nameLower, 'dusty pink')) {
            return 'https://images.unsplash.com/photo-1640923160720-35dddb6348ab?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'ribbon blouse') || str_contains($nameLower, 'peach')) {
            return 'https://images.unsplash.com/photo-1745381251702-67b07225e516?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'victorian') || str_contains($nameLower, 'lace')) {
            return 'https://images.unsplash.com/photo-1777462985111-9da64fb2e6e6?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'skirt') || str_contains($nameLower, 'plisket') || str_contains($nameLower, 'pleated')) {
            return 'https://images.unsplash.com/photo-1766238955752-0200601334ed?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'culottes') || str_contains($nameLower, 'kulot')) {
            return 'https://images.unsplash.com/photo-1687226426353-498d40ec6fbf?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'cardigan') || str_contains($nameLower, 'lavender')) {
            return 'https://images.unsplash.com/photo-1683315565563-f72590773805?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'cable knit') || str_contains($nameLower, 'sweater')) {
            return 'https://images.unsplash.com/photo-1641642231157-0849081598a2?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'hijab') || str_contains($nameLower, 'voile')) {
            return 'https://images.unsplash.com/photo-1592867874873-85480a35d2aa?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'pashmina') || str_contains($nameLower, 'scarf')) {
            return 'https://images.unsplash.com/photo-1619043599439-9b750b7b2623?auto=format&fit=crop&w=800&q=80';
        }

        return 'https://images.unsplash.com/photo-1572005700111-a96a2cb503c2?auto=format&fit=crop&w=800&q=80';
    }
}
