<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest\CreateCartRequest;
use App\Services\Cart\CheckingCartPerItemWithSummaryGroupingService;
use Illuminate\Http\Request;

class CartController extends BaseController
{
   /**
     * @lrd:start
     * # contoh format request untuk upsert
     * # ubah kutip nya menjadi kutip string
     * =============
    * 
    
    *         {
    *            "data": [
    *            {
    *            "variant_id":1,
    *            "qty":12,
    *            "note":"note"
    *            },
    *            {
    *             "variant_id":1,
    *            "qty":10,
    *              "note":"note"
    *            }
    *            ]
    *            }
    * =============
    * akhir dari format upsert
    * @lrd:end
    */
    public function create(CreateCartRequest $request,CheckingCartPerItemWithSummaryGroupingService $checkingCartPerItemWithSummaryGroupingService) 
    {
        
        $checkingdata = $checkingCartPerItemWithSummaryGroupingService->groupingPerItem($request->data);

        if($checkingdata['arrayStatus'] == true) {

            return $this->handleResponse($checkingdata['arrayResponse'],'cart success',$request->all(),str_replace('/','.',$request->path()),201);
        }

    }
}
