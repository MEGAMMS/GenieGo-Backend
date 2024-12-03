<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable=['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class);
    }
}
