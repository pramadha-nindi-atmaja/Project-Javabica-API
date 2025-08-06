<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStoreRequest\LocationStoreCreateRequest;
use App\Http\Requests\LocationStoreRequest\LocationStoreDestroyRequest;
use App\Http\Requests\LocationStoreRequest\LocationStoreShowRequest;
use App\Http\Requests\LocationStoreRequest\LocationStoreUpdateRequest;
use App\Interfaces\LocationStoreInterface;
use App\Models\Province;
use App\Models\Location_stores;
use Illuminate\Http\Request;
use App\Services\S3uploaderServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LocationStoreController extends BaseController
{
    private $locationInterface;
    private $s3uploaderService;

    public function __construct(LocationStoreInterface $locationInterface,S3uploaderServices $s3uploaderService)
    {
        $this->locationInterface            = $locationInterface;
        $this->s3uploaderService            = $s3uploaderService;
    }

    public function show(LocationStoreShowRequest $request) {

        $selectedColumn = array('*');
        $get = $this->locationInterface->show($request->all(),$selectedColumn,'show_all');
 
         if($get['queryStatus']) {
             
            //get province
           $getProvince =  Province::get();

           $output = array(
            'list_province' => $getProvince,
            'store_list'    => $get['queryResponse']
           );
           
            return $this->handleResponse( $output,'get location store success',$request->all(),str_replace('/','.',$request->path()),201);
         }
 
         $data  = array([
             'field' =>'show-location-store',
             'message'=> 'error when show location store'
         ]);
 
         return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);

    }

    public function update(LocationStoreUpdateRequest $request) {
        
        if( $request->file('image_upload')&&  $request->file('image_upload')  != null) {
            
            $this->uploaderValidation($request);

            $datas = Location_stores::find($request->id);
            $getimage = $datas->image;
            
            $fileData = $this->s3uploaderService->uploads3Storage($request->file('image_upload'),'dynamic',$getimage);

            $request->merge([
                'image' => $fileData['arrayResponse']['filePath']
            ]);

            
        } else {

            $request->merge([
                'image' => $request->image_upload ? $request->image_upload : NULL
            ]);
        }

        $update = $this->locationInterface->update($request->id,$request->except(['id']),'show_all');

        if($update['queryStatus']) {

            $data  = array(
                'field' =>'update-order',
                'message'=> 'order successfuly updated'
            );


            return $this->handleResponse($data,'Update location store success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'update-location store',
                'message'=> 'location store update fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }

    public function create(LocationStoreCreateRequest $request) {
        
        if( $request->file('image_upload') &&  $request->file('image_upload') != null) {
            $this->uploaderValidation($request);
            

            $fileData = $this->s3uploaderService->uploads3Storage($request->file('image_upload'),'dynamic');

            $request->merge([
                'image' => $fileData['arrayResponse']['filePath']
            ]);

        }
        else {
            $request->merge([
                'image' => $request->image_upload ? $request->image_upload : NULL
            ]);
        }

        $insertLocation  =   $this->locationInterface->store($request->all(),'show_all');

        if($insertLocation['queryStatus']) {

            return $this->handleResponse( $insertLocation['queryResponse'],'Insert location store success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-location',
                'message'=> 'location create fail'
            ]);

            return   $this->handleError($data,$insertLocation['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }
    public function province(Request $request) {

        $getProvince =  Province::get();
        return $this->handleResponse( $getProvince,'get location store success',$request->all(),str_replace('/','.',$request->path()),201);
    }

    public function delete(LocationStoreDestroyRequest $request) {
        
         //remove data
         $destroy =   $this->locationInterface->destroy($request->by_id);

         if($destroy['queryStatus']) {
 
             //response
             $data  = array(
                 'field' =>'destroy-location-stores',
                 'message'=> 'location stores successfuly destroyed'
             );
 
              return $this->handleResponse($data,'Destroy location stores  success',$request->all(),str_replace('/','.',$request->path()),204);
        
         } else {
             
             $data  = array([
                 'field' =>'destroy-location-stores',
                 'message'=> 'location stores destroy fail'
             ]);
 
              return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }

    }
    
    private function uploaderValidation($request) {
        $validator = Validator::make($request->only(['image']), [
            'image' =>  config('formValidation.image_upload'),       
        ]);

        if ($validator->fails()) { 
         
            throw ValidationException::withMessages( $validator->errors()->all());
        }
    }
}
