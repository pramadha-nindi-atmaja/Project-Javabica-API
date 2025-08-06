<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location_stores extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'fk_province',
        'description',
        'embed_map',
    ];

    public function variant_active_cheapest_price()
    {
         return $this->hasMany(Product_variant::class, 'fk_product_id', 'id')->orderBy('price','asc')->where('status','ACTIVE')->limit(1);
        
    }
    public function hasManyProvince()
    {
         return $this->hasOne(Province::class, 'id', 'fk_province');
        
    }
}
