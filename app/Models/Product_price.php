<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_price extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_product_id',
        'start_qty',
        'price',
        'discount',
    ];

    public function fk_product()
    {
        return $this->belongsTo(Product::class, 'fk_product_id', 'id');
    }


}
