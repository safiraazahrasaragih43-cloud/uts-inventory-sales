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

        // High-resolution visual fallback mapping based on product name/type
        $nameLower = strtolower($this->name);

        if (str_contains($nameLower, 'maroon')) {
            return 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'flanel') || str_contains($nameLower, 'plaid')) {
            return 'https://images.unsplash.com/photo-1589310243389-96a5483213a8?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'blus sutra') || (str_contains($nameLower, 'floral') && str_contains($nameLower, 'blus'))) {
            return 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'rose floral') || str_contains($nameLower, 'puff midi')) {
            return 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'satin wrap') || str_contains($nameLower, 'dusty pink')) {
            return 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'ribbon blouse') || str_contains($nameLower, 'peach')) {
            return 'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'victorian') || str_contains($nameLower, 'lace')) {
            return 'https://images.unsplash.com/photo-1551803091-e20673f15770?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'skirt') || str_contains($nameLower, 'plisket') || str_contains($nameLower, 'pleated')) {
            return 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'culottes') || str_contains($nameLower, 'kulot')) {
            return 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'cardigan') || str_contains($nameLower, 'lavender')) {
            return 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'cable knit') || str_contains($nameLower, 'sweater')) {
            return 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'hijab') || str_contains($nameLower, 'voile')) {
            return 'https://images.unsplash.com/photo-1609357605129-26f69add5d6e?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($nameLower, 'pashmina') || str_contains($nameLower, 'scarf')) {
            return 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?auto=format&fit=crop&w=800&q=80';
        }

        return 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80';
    }
}
