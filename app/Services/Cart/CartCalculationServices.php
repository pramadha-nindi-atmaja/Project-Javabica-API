<?php

namespace App\Services\Cart;

use App\Http\Controllers\BaseController;

/**
 * Class CartCalculationServices
 * @package App\Services
 */
class CartCalculationServices extends BaseController
{
    public function cartCalculation($price,$qty) {

        return $price*$qty;
        
    }
}
