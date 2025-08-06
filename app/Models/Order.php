<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
  
    protected $fillable = [
        'uuid',
        'queue_number',
        'order_number',
        'contact_email', 
        
        'contact_phone', 
        'shipping_country',
        'shipping_first_name', 
        'shipping_last_name', 
        'shipping_address',
        'shipping_city', 
        'shipping_province', 
        'shipping_postal_code', 
        'shipping_label_place', 
        'shipping_note_address', 

        'contact_billing_phone',
        'billing_country',
        'billing_first_name', 
        'billing_last_name', 
        'billing_address',
        'billing_city', 
        'billing_province', 
        'billing_postal_code', 
        'billing_label_place', 
        'billing_note_address', 

       
        'courier_agent',
        'courier_agent_service', 
        'courier_agent_service_desc',
        'courier_estimate_delivered', 


        'courier_resi_number', 
        'courier_cost',
        
        'payment_method', 
        'payment_snap_token', 
        'payment_refrence_code',

        'invoice_note', 
        'delivery_order_note',
        
        'fk_user_id', 

        'fk_voucher_id', 
        
        'payment_status',
        'status', 
 
    ];

    public function fk_user()
    {
        return $this->belongsTo(User::class, 'fk_user_id', 'id');
    }
    public function fk_voucher()
    {
        return $this->belongsTo(Voucher::class, 'fk_voucher_id', 'id');
    }
    public function getLatestQueueNumber_permonth()
    {
        return $this->whereMonth('created_at', Carbon::now()->month)->order_by('queue_number','desc');
    }
    public function hasMany_orderProduct()
    {
         return $this->hasMany(Order_product::class, 'fk_order_id', 'id');
        
    }
}
