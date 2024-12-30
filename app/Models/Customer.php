<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'customer_sites', 'customer_id', 'site_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
