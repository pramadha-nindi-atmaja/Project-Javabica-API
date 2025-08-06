<?php

namespace App\Services\RajaOngkir;

use App\Http\Controllers\BaseController;

/**
 * Class CourierFinderServices
 * @package App\Services
 */
class CourierFinderServices extends BaseController
{
    //find service
    public function courierCostFinder(array $listServiceCost,$courierService)
    {
        $selectedArray = [];
       
        foreach($listServiceCost as $findCourier)
        {
            if($findCourier['service'] == $courierService['service'])
            {
                // if($findCourier['cost'][0]['value'] == $courierService['price'])
                // {
                // }
                array_push($selectedArray,$findCourier);
            }
        }

        if(empty($selectedArray))
        {
            return $this->handleArrayErrorResponse($selectedArray, 'courier not found', 'danger');
        }

        return $this->handleArrayResponse($selectedArray, 'courier found', 'info');
    }
}
