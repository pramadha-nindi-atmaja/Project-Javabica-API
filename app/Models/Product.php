<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 
        'slug', 
        'is_freeshiping',
        'product_description',
        'product_information', 
        'meta_keywords', 
        'meta_description',
        'meta_title', 

        'weight', 
        'type_weight', 
        'size_long',
        'size_tall', 
        'size_wide',
        'type_size',
        'sort', 


        'tags', 
        'status',
    ];
    public function hasMany_category()
    {
         return $this->hasMany(Product_categories::class, 'fk_product_id', 'id');
        
    }
    public function hasMany_collection()
    {
         return $this->hasMany(Product_collection::class, 'fk_product_id', 'id');
        
    }
    public function hasMany_variant()
    {
         return $this->hasMany(Product_variant::class, 'fk_product_id', 'id');
        
    }
    public function variant_cheapest_price()
    {
         return $this->hasMany(Product_variant::class, 'fk_product_id', 'id')->orderBy('price','asc')->limit(1);
        
    }
    public function hasMany_image()
    {
         return $this->hasMany(Product_image::class, 'fk_product_id', 'id')->orderBy('order_number','asc');
        
    }

    public function hasMany_wholesalePrice()
    {
         return $this->hasMany(Product_price::class, 'fk_product_id', 'id')->orderBy('start_qty','asc');
        
    }

    //for publics query purpose
    public function hasMany_variantActive()
    {
         return $this->hasMany(Product_variant::class, 'fk_product_id', 'id')->where('status',"ACTIVE")->orderBy('price','asc');
        
    }
    public function hasMany_imageThumbnail()
    {
         return $this->hasMany(Product_image::class, 'fk_product_id', 'id')->limit(2)->orderBy('order_number','asc');
        
    }
    public function hasMany_image_getPrimaryImage()
    {
         return $this->hasOne(Product_image::class, 'fk_product_id', 'id')->limit(1)->orderBy('order_number','asc');
        
    }
    public function variant_active_cheapest_price()
    {
         return $this->hasMany(Product_variant::class, 'fk_product_id', 'id')->orderBy('price','asc')->where('status','ACTIVE')->limit(1);
        
    }
    public function variant_active_or_inactive_cheapest_price()
    {
         return $this->hasMany(Product_variant::class, 'fk_product_id', 'id')->orderBy('price','asc')->limit(1);
        
    }
}
