<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_categories extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'fk_product_id',
        'fk_category_id', 
    ];

    public function fk_product()
    {
        return $this->belongsTo(Product::class, 'fk_product_id', 'id');
    }
    public function fk_category()
    {
        return $this->belongsTo(Taxo_list::class, 'fk_category_id', 'id')->whereIn('taxonomy_type',[2,3]);
    }
    
   
}
