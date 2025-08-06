<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'fk_product_id',
        'fk_collection_id', 
    ];

    public function fk_product()
    {
        return $this->belongsTo(Product::class, 'fk_product_id', 'id');
    }

    public function fk_collection()
    {
        return $this->belongsTo(Taxo_list::class, 'fk_collection_id', 'id')->where('taxonomy_type',1);
    }
}
