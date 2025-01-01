<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['price','stock','store_id'];

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translation($lang = 'en')
    {
        return $this->translations()->where('language', $lang)->first();
    }

    public function tags()
    {
         return $this->belongsToMany(Tag::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Check if the product is in stock.
     */
    public function inStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Reduce stock after a purchase.
     */
    public function reduceStock(int $quantity): void
    {
        if ($this->inStock($quantity)) {
            $this->decrement('stock', $quantity);
        } else {
            throw new \Exception('Insufficient stock available.');
        }
    }

    /**
     * Increase stock (e.g., during restocking or returns).
     */
    public function increaseStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }
}
