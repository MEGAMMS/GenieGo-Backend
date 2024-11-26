<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'language',
        'name',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
