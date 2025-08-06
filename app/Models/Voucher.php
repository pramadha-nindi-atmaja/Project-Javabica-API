<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'amount',
        'min_payment',
        'start_date',
        'end_date',
        'max_usage',
        'total',
        'is_active',
    ];

    function generateUniqueCode()
    {
        // Generate a random unique code here
        $code = ''; // Initial code
        $length = 8; // Length of the code
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $max = strlen($characters) - 1;

        // Generate the random code (you can modify this part)
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = mt_rand(0, $max);
            $code .= $characters[$randomIndex];
        }

        return $code;
    }
    
    public function history_voucher()
    {
        return $this->hasMany(HistoryVoucher::class, 'voucher_id', 'id');
    }
}
