<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingAddressRequest\ShippingAddressCreateRequest;
use App\Http\Requests\ShippingAddressRequest\ShippingAddressDestroyRequest;
use App\Http\Requests\ShippingAddressRequest\ShippingAddressGetRequest;
use App\Http\Requests\ShippingAddressRequest\ShippingAddressUpdateRequest;
use App\Interfaces\ShippingAddressInterface;
use App\Services\RajaOngkir\CityServices;
use Illuminate\Http\Request;

class ShippingAddressController extends BaseController
{
    private $shippingAddressInterface;
   
    
    public function __construct(ShippingAddressInterface $shippingAddressInterface)
    {
        $this->shippingAddressInterface            = $shippingAddressInterface;
     
    }
  
    public function show(ShippingAddressGetRequest $request) {

        $selectedColumn = array('*');

        $get = $this->shippingAddressInterface->show($request->all(),$selectedColumn);

        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get shipping address success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-user',
            'message'=> 'error when show shipping address'
        ]);

        return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
    }
    public function create(ShippingAddressCreateRequest $request, CityServices $cityService) {
        // Validate the city input
        if (empty($request->city)) {
            return $this->handleError(
                ['field' => 'city', 'message' => 'City is required'],
                'City data missing',
                $request->all(),
                str_replace('/', '.', $request->path()),
                422
            );
        }
    
        // Log the start of the operation
        Log::info('Creating shipping address', ['payload' => $request->all()]);
    
        // Calling Raja Ongkir to find label
        $payloadCity = array(
            'id' => $request->city,
            'province_id' => null
        );
        $getCity = $cityService->getCity($payloadCity);
    
        if (!$getCity['arrayResponse']) {
            return $this->handleError(
                ['field' => 'city', 'message' => 'Failed to retrieve city data'],
                'City service response error',
                $request->all(),
                str_replace('/', '.', $request->path()),
                422
            );
        }
    
        $request->merge([
            'city' => $getCity['arrayResponse']['city_id'],
            'city_label' => $getCity['arrayResponse']['city_name'],
            'province_label' => $getCity['arrayResponse']['province'],
            'province' => $getCity['arrayResponse']['province_id']
        ]);
    
        $insert = $this->shippingAddressInterface->store($request->all(), 'show_all');
    
        if ($insert['queryStatus']) {
            Log::info('Shipping address created successfully', ['response' => $insert['queryResponse']]);
    
            return $this->handleResponse(
                $insert['queryResponse'],
                'Insert shipping address success',
                $request->all(),
                str_replace('/', '.', $request->path()),
                201
            );
        } else {
            $data = array([
                'field' => 'create-shipping address-product',
                'message' => 'Shipping address product creation failed'
            ]);
    
            // Log the failure
            Log::error('Failed to create shipping address', ['error' => $insert['queryMessage']]);
    
            return $this->handleError(
                $data,
                $insert['queryMessage'],
                $request->all(),
                str_replace('/', '.', $request->path()),
                422
            );
        }
    }

    public function destroy(ShippingAddressDestroyRequest $request) {
        // Log operasi yang dimulai
        Log::info('Destroying shipping address', ['id' => $request->by_id]);
    
        // Validasi input ID shipping address
        if (empty($request->by_id)) {
            Log::error('Failed to destroy shipping address: ID missing');
            return $this->handleError(
                ['field' => 'by_id', 'message' => 'Shipping address ID is required'],
                'Missing shipping address ID',
                $request->all(),
                str_replace('/', '.', $request->path()),
                422
            );
        }
    
        // Remove data dari storage
        $destroyAdmin = $this->shippingAddressInterface->destroy($request->by_id);
    
        if ($destroyAdmin['queryStatus']) {
            // Log operasi yang berhasil
            Log::info('Shipping address destroyed successfully', ['id' => $request->by_id]);
    
            // Response jika sukses
            $data = array(
                'field' => 'destroy-shipping address',
                'message' => 'Shipping address successfully destroyed'
            );
    
            return $this->handleResponse($data, 'Destroy shipping address success', $request->all(), str_replace('/', '.', $request->path()), 204);
        } else {
            // Log error jika gagal
            Log::error('Failed to destroy shipping address', ['id' => $request->by_id, 'error' => $destroyAdmin['queryMessage']]);
    
            // Response jika gagal
            $data = array([
                'field' => 'destroy-shipping address',
                'message' => 'Shipping address destruction failed'
            ]);
    
            return $this->handleError($data, $destroyAdmin['queryMessage'], $request->all(), str_replace('/', '.', $request->path()), 422);
        }
    }

    public function update(ShippingAddressUpdateRequest $request, CityServices $cityService) {
        // Log awal proses update
        Log::info('Updating shipping address', ['id' => $request->id, 'payload' => $request->all()]);
    
        // Validasi input city
        if (empty($request->city)) {
            Log::error('Failed to update shipping address: City data is missing');
            return $this->handleError(
                ['field' => 'city', 'message' => 'City is required'],
                'City data missing',
                $request->all(),
                str_replace('/', '.', $request->path()),
                422
            );
        }
    
        // Panggil Raja Ongkir untuk menemukan label
        $payloadCity = array(
            'id' => $request->city,
            'province_id' => null
        );
        $getCity = $cityService->getCity($payloadCity);
    
        // Validasi respons dari CityServices
        if (!$getCity['arrayResponse']) {
            Log::error('Failed to update shipping address: Error in CityServices response');
            return $this->handleError(
                ['field' => 'city', 'message' => 'Failed to retrieve city data'],
                'City service response error',
                $request->all(),
                str_replace('/', '.', $request->path()),
                422
            );
        }
    
        $request->merge([
            'city' => $getCity['arrayResponse']['city_id'],
            'city_label' => $getCity['arrayResponse']['city_name'],
            'province_label' => $getCity['arrayResponse']['province'],
            'province' => $getCity['arrayResponse']['province_id']
        ]);
    
        // Update data
        $update = $this->shippingAddressInterface->update($request->id, $request->except(['id']), 'show_all');
    
        if ($update['queryStatus']) {
            // Log keberhasilan
            Log::info('Shipping address updated successfully', ['id' => $request->id, 'response' => $update['queryResponse']]);
    
            return $this->handleResponse(
                $update['queryResponse'],
                'Update shipping address success',
                $request->all(),
                str_replace('/', '.', $request->path()),
                201
            );
        } else {
            // Log kegagalan
            Log::error('Failed to update shipping address', ['id' => $request->id, 'error' => $update['queryMessage']]);
    
            $data = array([
                'field' => 'update-shipping',
                'message' => 'Shipping address update failed',
                'debug_info' => $update['debugInfo'] ?? null // Sertakan informasi debug jika tersedia
            ]);
    
            return $this->handleError(
                $data,
                $update['queryMessage'],
                $request->all(),
                str_replace('/', '.', $request->path()),
                422
            );
        }
    }
}
