<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxo_list extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent',
        'taxonomy_ref_key',
        'taxonomy_name',
        'taxonomy_description',

        'taxonomy_slug',
        'taxonomy_type',
        'taxonomy_image',

        'taxonomy_sort',
        'taxonomy_status',
    ];

    public function taxoType()
    {
        return $this->belongsTo(Taxo_type::class, 'taxonomy_type', 'id');
    }
    public function taxoParent()
    {
        return $this->belongsTo(Taxo_list::class, 'parent', 'id');
    }
    public function taxoChild()
    {
        return $this->hasMany(Taxo_list::class, 'parent', 'id');
    }
    //publics
    public function taxoChild_publics()
    {
        return $this->hasMany(Taxo_list::class, 'parent', 'id')->where('taxonomy_status','ACTIVE')->orderBy('taxonomy_sort','asc');
    }
   


}
