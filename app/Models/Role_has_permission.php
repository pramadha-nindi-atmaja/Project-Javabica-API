<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_has_permission extends Model
{
    use HasFactory;

    public function belogsTo_Permission() {
        return $this->belongsTo(Permission::class, 'permission_id', 'id'); // list of country
    }
    public function belogsTo_Role() {
        return $this->belongsTo(Role::class, 'role_id', 'id'); // list of country
    }
}
