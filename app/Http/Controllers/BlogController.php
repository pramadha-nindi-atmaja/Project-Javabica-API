<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest\GetBlogRequestValidation;
use App\Http\Requests\BlogRequest\DestroyBlogRequest;
use App\Http\Requests\BlogRequest\CreateBlogRequest;
use App\Http\Requests\BlogRequest\UpdateBlogRequest;
use App\Interfaces\BlogInterface;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\S3uploaderServices;

class BlogController extends BaseController
{
    private $blogInterface;
    private $s3uploaderService;

    
    public function __construct(BlogInterface $blogInterface,S3uploaderServices $s3uploaderService)
    {
        $this->blogInterface            = $blogInterface;
        $this->s3uploaderService            = $s3uploaderService;
    
    }
    public function show(GetBlogRequestValidation $request) {

        $selectedColumn = array('*');

        $getBlog = $this->blogInterface->show($request,$selectedColumn);
        
        if($getBlog['queryStatus']) {
            
            return $this->handleResponse( $getBlog['queryResponse'],'get Blog success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-blog',
            'message'=> 'error when show Blog '
        ]);

        return   $this->handleError( $data,$getBlog['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
    }
    public function create(CreateBlogRequest $request) { 

        if( $request->file('cover_upload') &&  $request->file('cover_upload') != null) {
            $this->uploaderValidation($request);
            

            $fileData = $this->s3uploaderService->uploads3Storage($request->file('cover_upload'),'dynamic');

            $request->merge([
                'cover' => $fileData['arrayResponse']['filePath']
            ]);

        }
        else {
            $request->merge([
                'cover' =>$request->cover_upload
            ]);
        }

        $insert = $this->blogInterface->store($request->all(),'show_all');

        if($insert['queryStatus']) {

            return $this->handleResponse( $insert['queryResponse'],'Insert blog  success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-blog',
                'message'=> 'blog create fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }
    public function update(UpdateBlogRequest $request) { //done
        
        if( $request->file('cover_upload')&&  $request->file('cover_upload')  != null) {
            
            $this->uploaderValidation($request);

            $datas = Blog::find($request->id);
            $getimage = $datas->cover;
            
            $fileData = $this->s3uploaderService->uploads3Storage($request->file('cover_upload'),'dynamic',$getimage);

            $request->merge([
                'cover' => $fileData['arrayResponse']['filePath']
            ]);

            
        } else {

            $request->merge([
                'cover' =>$request->cover_upload
            ]);
        }

        $update = $this->blogInterface->update($request->id,$request->except(['id']),'show_all');

        if($update['queryStatus']) {

            $data  = array(
                'field' =>'update-blog',
                'message'=> 'blog successfuly updated'
            );
            
            return $this->handleResponse( $update['queryResponse'],'update blog success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'update-blog',
                'message'=> 'blog update fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }

    public function delete(DestroyBlogRequest $request) {
        $destroy =   $this->blogInterface->destroy($request->by_id);

        if($destroy['queryStatus']) {

            return $this->handleResponse( $destroy['queryResponse'],'delete blog success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'destroy-blog',
                'message'=> 'blog delete fail'
            ]);

            return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
    }

    private function uploaderValidation($request) {
        $validator = Validator::make($request->only(['cover']), [
            'cover' =>  config('formValidation.image_upload'),       
        ]);

        if ($validator->fails()) { 
         
            throw ValidationException::withMessages( $validator->errors()->all());
        }
    }

}
