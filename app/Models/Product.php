<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'price'];

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translation($lang = 'en')
    {
        return $this->translations()->where('language', $lang)->first();
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
