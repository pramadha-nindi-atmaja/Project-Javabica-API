<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\BlogRequest\PublicBlogGetRequest;
use App\Interfaces\BlogInterface;
use Illuminate\Http\Request;

class ShowPublicBlogsController extends BaseController
{
    private $blogInterface;
    private $handleOutputVariantProductService;
   
    
    public function __construct(BlogInterface $blogInterface)
    {
        $this->blogInterface        = $blogInterface;
   
    }
    public function show(PublicBlogGetRequest $request) {

      
        $selectedColumn = array('*');

        $get = $this->blogInterface->show($request,$selectedColumn);
  

        if ($get['queryStatus']) {

            return $this->handleResponse($get['queryResponse'], 'get Blog success', $request->all(), str_replace('/', '.', $request->path()), 201);
        }

        $data  = array([
            'field' => 'show-blog',
            'message' => 'error when show blog'
        ]);
       
        return   $this->handleError($data, $get['queryMessage'], $request->all(), str_replace('/', '.', $request->path()), 422);
    }
}
