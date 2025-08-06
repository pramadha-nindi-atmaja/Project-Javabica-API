<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\VoucherRequest\PublicVoucherGetRequest;
use App\Interfaces\VoucherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowPublicVoucherController extends BaseController
{
    private $voucherInterface;
    private $handleOutputVariantVoucherService;
    
    public function __construct(VoucherInterface $voucherInterface)
    {
        $this->voucherInterface                             = $voucherInterface;
    }
    public function show(PublicVoucherGetRequest $request) {
        
        $selectedColumn = array('*');
    
        $get = $this->voucherInterface->check_voucher($request,$selectedColumn);

        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),201);
        }
        $data  = array([
            'field' =>'show-voucher',
            'message'=> 'error when show voucher'
        ]);

        return  $this->handleError( $data,$get['queryResponse'],$request->all(),str_replace('/','.',$request->path()),422);

    }
}
