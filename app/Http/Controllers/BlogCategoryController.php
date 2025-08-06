<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\BlogCategoryInterface;
use App\Http\Requests\BlogCategoryRequest\GetCategoryBlogRequestValidation;
use App\Http\Requests\BlogCategoryRequest\CreateCategoryBlogRequest;
use App\Http\Requests\BlogCategoryRequest\UpdateCategoryBlogRequest;
use App\Http\Requests\BlogCategoryRequest\DestroyCategoryBlogRequest;


class BlogCategoryController extends BaseController
{
    private $blogCategoryInterface;
   
    
    public function __construct(BlogCategoryInterface $blogCategoryInterface)
    {
        $this->blogCategoryInterface            = $blogCategoryInterface;
    }

    public function show(GetCategoryBlogRequestValidation $request) {

        $selectedColumn = array('*');

        $getBlogCategory = $this->blogCategoryInterface->show($request,$selectedColumn);
        
        if($getBlogCategory['queryStatus']) {
            
            return $this->handleResponse( $getBlogCategory['queryResponse'],'get Blog Category success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-blog-category',
            'message'=> 'error when show Blog Category'
        ]);

        return   $this->handleError( $data,$getBlogCategory['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
    }

    public function create(CreateCategoryBlogRequest $request) { 

  
        $insert = $this->blogCategoryInterface->store($request->all(),'show_all');
    
        if($insert['queryStatus']) {

            return $this->handleResponse( $insert['queryResponse'],'Insert blog Category  success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-blog-category',
                'message'=> 'blog category create fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

        
    }

    public function update(UpdateCategoryBlogRequest $request) { 
        
        $update = $this->blogCategoryInterface->update($request->id,$request->except(['id']),'show_all');
          
        if($update['queryStatus']) {

            return $this->handleResponse( $update['queryResponse'],'update blog Category success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-blog-category',
                'message'=> 'blog create fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }

    public function delete(DestroyCategoryBlogRequest $request) { //done
        
        //remove data
        $destroy =   $this->blogCategoryInterface->destroy($request->by_id);


        if($destroy['queryStatus']) {

            return $this->handleResponse( $destroy['queryResponse'],'Delete blog Category success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'destroy-blog-category',
                'message'=> 'blog category create fail'
            ]);

            return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

}


}
