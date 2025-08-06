<?php

namespace App\Http\Controllers;

use App\Http\Requests\RajaOngkirRequest\GetCityRequest;
use App\Http\Requests\RajaOngkirRequest\GetCostRequest;
use App\Http\Requests\RajaOngkirRequest\GetProvinceRequest;
use App\Services\RajaOngkir\CityServices;
use App\Services\RajaOngkir\CostServices;
use App\Services\RajaOngkir\ProvinceServices;
use Illuminate\Http\Request;

class ShippingController extends BaseController
{
    public function province(GetProvinceRequest $request, ProvinceServices $provinceServices) {

        $getProvince = $provinceServices->getProvince($request->all());
        
        if($getProvince['arrayStatus'] == true) {

            return $this->handleResponse( $getProvince['arrayResponse'],'get province by raja ongkir success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'raja-ongkir',
            'message'=> 'raja ongkir province connection fail'
        ]);

        return   $this->handleError($data,$getProvince['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);
    }
    public function city(GetCityRequest $request, CityServices $cityServices) {

        $getCity = $cityServices->getCity($request->all());
        
        if($getCity['arrayStatus'] == true) {

            return $this->handleResponse( $getCity['arrayResponse'],'get city by raja ongkir success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'raja-ongkir',
            'message'=> 'raja ongkir city connection fail'
        ]);

        return   $this->handleError($data,$getCity['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);
    }
    public function cost(GetCostRequest $request,CostServices $costServices) {
        
        $getCost = $costServices->getCost($request->all());

        if($getCost['arrayStatus'] == true) {

            return $this->handleResponse( $getCost['arrayResponse'],'get city by raja ongkir success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'raja-ongkir',
            'message'=> 'raja ongkir cost connection fail'
        ]);

        return   $this->handleError($data,$getCost['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);

    }
}
