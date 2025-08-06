<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherRequest\VoucherCreateRequest;
use App\Http\Requests\VoucherRequest\VoucherDestroyRequest;
use App\Http\Requests\VoucherRequest\VoucherGetRequest;
use App\Http\Requests\VoucherRequest\VoucherUpdateRequest;
use App\Interfaces\VoucherInterface;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class VoucherController extends BaseController
{
    private $voucherInterface;

    public function __construct(VoucherInterface $voucherInterface)
    {
        $this->voucherInterface            = $voucherInterface;
    }

    public function show(VoucherGetRequest $request) {

        $selectedColumn = array('*');
        $get = $this->voucherInterface->show($request->all(),$selectedColumn,'show_all');

        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get voucher success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-voucher',
            'message'=> 'error when show voucher'
        ]);

        return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);

    }

    public function create(VoucherCreateRequest $request) { 

        //get voucher code
        if($request->code == null)
        {
            $voucher = new Voucher(); 
            $code = $voucher->generateUniqueCode();

            $request->merge([
                'code'  => $code,
            ]);
        }

        //insert
        $insert = $this->voucherInterface->store($request->all(),'show_all');
        
        if($insert['queryStatus']) {

            return $this->handleResponse( $insert['queryResponse'],'Insert voucher success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-voucher',
                'message'=> 'voucher create fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
        
    }
    public function update(VoucherUpdateRequest $request) { 
        
        if($request->code == null)
        {
            $voucher = new Voucher(); 
            $code = $voucher->generateUniqueCode();

            $request->merge([
                'code'  => $code,
            ]);
        }

        $update = $this->voucherInterface->update($request->id,$request->except(['id']),'show_all');

        if($update['queryStatus']) {

            $data  = array(
                'field' =>'update-voucher',
                'message'=> 'voucher successfuly updated'
            );

            return $this->handleResponse($data,'Update voucher success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'update-voucher',
                'message'=> 'voucher update fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }

    public function destroy(VoucherDestroyRequest $request) { 
        
        //remove data
        $destroy =   $this->voucherInterface->destroy($request->by_id);

        if($destroy['queryStatus']) {

            //response
            $data  = array(
                'field' =>'destroy-voucher',
                'message'=> 'voucher successfuly destroyed'
            );

            return $this->handleResponse($data,'Destroy voucher  success',$request->all(),str_replace('/','.',$request->path()),204);
    
        } else {
            
            $data  = array([
                'field' =>'destroy-voucher',
                'message'=> 'voucher destroy fail'
            ]);

            return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
        
    }
}
