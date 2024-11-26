<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSite extends Model
{
    use HasFactory;

    protected $fillasble=['site_id','customer_id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /*
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
     */
}
