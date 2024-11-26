<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'address',
    ];

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
    
    public function translations()
    {
        return $this->hasMany(SiteTranslation::class);
    }

    public function translation($lang = 'en')
    {
        return $this->translations()->where('language', $lang)->first();
    }

    
    public function customerSite()
    {
        return $this->hasMany(CustomerSite::class);
    }
    
}
