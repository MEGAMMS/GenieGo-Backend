<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['site_id', 'name', 'address'];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    
    public function translations()
    {
        return $this->hasMany(StoreTranslation::class);
    }

    public function translation($lang = 'en')
    {
        return $this->translations()->where('language', $lang)->first();
    }

    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /*
    public function tags()
    {
        return $this->hasMany(StoreTags::class);
    }*/
 
    public function products()
    {
        return $this->hasMany(product::class);
    }
}
