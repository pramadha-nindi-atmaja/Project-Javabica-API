<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_product extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_product_id',
        'fk_variant_id',
        'product_name',
        'image',
        'sku', 
        'variant_description', 
        'qty', 
        'acctual_price', 
        'discount_price',
        'purchase_price', 
        'fk_order_id', 
        'note',

    ];

    public function fk_order()
    {
        return $this->belongsTo(Order::class, 'fk_order_id', 'id');
    }
    public function fk_product()
    {
        return $this->belongsTo(Product::class, 'fk_product_id', 'id');
    }
    public function fk_variant()
    {
        return $this->belongsTo(Product_variant::class, 'fk_variant_id', 'id');
    }
   
}
