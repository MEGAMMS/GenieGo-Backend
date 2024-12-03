<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'address',
    ];

    public function store()
    {
        return $this->hasOne(Store::class);
    }
    
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    
}
