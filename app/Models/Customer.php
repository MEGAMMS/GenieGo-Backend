<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable=['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->hasMany(CustomerSite::class);
    }

    /*
    public function orders()
    {
        return $this->hasMany(Orders::class);
    }*/
}
