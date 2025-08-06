<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_shipping_address extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'label_place',
        'courier_note',
        'address',
        'city',
        'city_label',
        'province',
        'province_label',
        'fk_user_id',
        'postal_code',
    ];

    public function fk_users()
    {
        return $this->belongsTo(User::class, 'fk_user_id', 'id');
    }
}
