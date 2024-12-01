<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['price'];

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
         return $this->hasMany(Tag::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
