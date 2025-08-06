<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxonomyRequest\TaxonomyCreateRequestValidation;
use App\Http\Requests\TaxonomyRequest\TaxonomyDestroyRequestValidation;
use App\Http\Requests\TaxonomyRequest\TaxonomyGetRequestValidation;
use App\Http\Requests\TaxonomyRequest\TaxonomyUpdateRequestValidation;
use App\Interfaces\TaxonomyInterface;
use App\Models\Taxo_list;
use App\Services\S3uploaderServices;
use App\Services\UploaderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use  Illuminate\Validation\ValidationException;
class TaxonomyController extends BaseController
{
    private $taxonomyInterface;
    private $s3uploaderService;
    
    public function __construct(TaxonomyInterface $taxonomyInterface,S3uploaderServices $s3uploaderService)
    {
        $this->taxonomyInterface            = $taxonomyInterface;
        $this->s3uploaderService            = $s3uploaderService;
    }
     /**
     * @lrd:start
     * # keyword untuk pencarian akan mencari :  taxonomy name dan atau taxonomy slug dan atau taxonomy parent dan atau taxonomy type
     *
     *
     * @lrd:end
     */
    public function show(TaxonomyGetRequestValidation $request) {

      
       
            $selectedColumn = array('*');

             $getTaxonomy = $this->taxonomyInterface->show($request,$selectedColumn);
        
        if($getTaxonomy['queryStatus']) {
            
            return $this->handleResponse( $getTaxonomy['queryResponse'],'get Taxonomy success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-user',
            'message'=> 'error when show taxonomy'
        ]);

        return   $this->handleError( $data,$getTaxonomy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);

    }
    public function show_type() {

        $getTaxonomy = $this->taxonomyInterface->show_type();

        return $this->handleResponse( $getTaxonomy['queryResponse'],'get Taxonomy type success',null,null,201);


    }
    public function create(TaxonomyCreateRequestValidation $request,Taxo_list $taxonomy) {

        //$this->authorize('create',$taxonomy);
         //upload file if exist
         if( $request->file('taxonomy_image_upload') &&  $request->file('taxonomy_image_upload') != null) {
            
            $this->uploaderValidation($request);
            
            $fileData = $this->s3uploaderService->uploads3Storage($request->file('taxonomy_image_upload'),'dynamic');
          
            $request->merge([
                'taxonomy_image' => $fileData['arrayResponse']['filePath']
            ]);

            
        }
        else {

            $request->merge([
                'taxonomy_image' =>$request->taxonomy_image_upload
            ]);
        }
     
        $insert = $this->taxonomyInterface->store($request->all(),'showAll');
        
        if($insert['queryStatus']) {

            return $this->handleResponse( $insert['queryResponse'],'Insert Taxonomy success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {
            $data  = array([
                'field' =>'create-taxonomy',
                'message'=> 'taxonomy create fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
       
    }
    public function update(TaxonomyUpdateRequestValidation $request,Taxo_list $taxonomy) {
        
        //$this->authorize('update',$taxonomy);
      
        //upload file if exist
        if( $request->file('taxonomy_image_upload')&&  $request->file('taxonomy_image_upload')  != null) {
            
            $this->uploaderValidation($request);

            $datas = Taxo_list::find($request->id);
            $getimage = $datas->taxonomy_image;
            
            $fileData = $this->s3uploaderService->uploads3Storage($request->file('taxonomy_image_upload'),'dynamic',$getimage);
          
            $request->merge([
                'taxonomy_image' => $fileData['arrayResponse']['filePath']
            ]);

            
        } else {

            $request->merge([
                'taxonomy_image' =>$request->taxonomy_image_upload
            ]);
        }
        
        $update = $this->taxonomyInterface->update($request->id,$request->except(['id']),'showAll');

        if($update['queryStatus']) {

            $data  = array(
                'field' =>'update-taxonomy',
                'message'=> 'taxonomy successfuly updated'
            );


            return $this->handleResponse($data,'Update Taxonomy success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'update-taxonomy',
                'message'=> 'taxonomy destroy fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
  
    }
    public function destroy(TaxonomyDestroyRequestValidation $request,Taxo_list $taxonomy) {

        //$this->authorize('destroy',$taxonomy);
         
         //remove image
        $datas = Taxo_list::find($request->by_id);

        if($datas->taxonomy_type == 5)
        {
            $getimage = $datas->taxonomy_image;

            if($getimage != null) {
               $this->s3uploaderService->removeFile($getimage);
            }
        }
      

          //remove data
        $destroyAdmin =   $this->taxonomyInterface->destroy($request->by_id);

        if($destroyAdmin['queryStatus']) {

            //response
            $data  = array(
                'field' =>'destroy-taxonomy',
                'message'=> 'taxonomy successfuly destroyed'
            );

             return $this->handleResponse($data,'Destroy taxonomy  success',$request->all(),str_replace('/','.',$request->path()),204);
       
        } else {
            
            $data  = array([
                'field' =>'destroy-taxonomy',
                'message'=> 'taxonomy destroy fail'
            ]);

             return   $this->handleError($data,$destroyAdmin['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
    }
    private function uploaderValidation($request) {
        $validator = Validator::make($request->only(['taxonomy_image_upload']), [
            'taxonomy_image_upload' =>  config('formValidation.image_upload'),       
        ]);

        if ($validator->fails()) { 
         
            throw ValidationException::withMessages( $validator->errors()->all());
        }
    }

}
