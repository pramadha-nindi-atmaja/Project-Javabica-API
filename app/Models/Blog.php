<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cover',
        'title',
        'short_desc',
        'long_desc',
        'fk_category',
        'slug',
        'status',
        'hot_news',
        'meta_title',
        'meta_description',
    ];

    protected $with = [
        "category"
    ];
    
    public function category()
    {
        return $this->belongsTo(CategoryBlog::class, 'fk_category', 'id'); // Mengubah relasi ke model CategoryBlog
    }
}
