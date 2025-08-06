<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Model_has_permission extends Model
{
    use HasFactory;
    
    protected $table = 'model_has_permissions';
    
    public function user()
    {
        return $this->hasMany(User::class, 'model_id', 'id');
    }

    public function permission() {
        return $this->belongsTo(Permission::class, 'permission_id', 'id'); // list of country
    }
}
