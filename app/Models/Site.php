<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
    ];

    public function store()
    {
        // A Site belongs to one Customer via pivot table
        return $this->belongsToMany(Customer::class, 'customer_sites', 'site_id', 'customer_id')->withTimestamps();
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}
