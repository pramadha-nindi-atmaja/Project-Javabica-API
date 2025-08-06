<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_product_id',
        'fk_attribute_parent_id',
        'fk_attribute_child_id',
        'sku',
        'image_path',
        'stock',
        'price',
        'discount',
        'is_ignore_stock',
        'status',
    ];

    public function fk_product()
    {
        return $this->belongsTo(Product::class, 'fk_product_id', 'id');
    }
    public function fk_attribute_parent()
    {
        return $this->belongsTo(Taxo_list::class, 'fk_attribute_parent_id', 'id')->where('taxonomy_type',6);
    }
    public function fk_attribute_child()
    {
        return $this->belongsTo(Taxo_list::class, 'fk_attribute_child_id', 'id')->where('taxonomy_type',6);
    }
    public function fk_attribute_child_from_parent()
    {
        return $this->belongsTo(Taxo_list::class, 'fk_attribute_child_id', 'id')->where('taxonomy_type',6);
    }
}
