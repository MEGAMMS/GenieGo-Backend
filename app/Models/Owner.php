<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    
    public function store()
    {
        return $this->belongTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
