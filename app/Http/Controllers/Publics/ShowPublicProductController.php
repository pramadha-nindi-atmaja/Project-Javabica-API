<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\ProductRequest\PublicProductGetRequest;
use App\Interfaces\ProductInterface;
use Illuminate\Http\Request;

class ShowPublicProductController extends BaseController
{
    private $productInterface;
    private $handleOutputVariantProductService;
   
    
    public function __construct(ProductInterface $productInterface)
    {
        $this->productInterface                             = $productInterface;
   
    }
    public function show(PublicProductGetRequest $request) {
       
        if($request->is_detail == true) 
        {
            $collectionOuput = 'show_product_detail';
        }
        else 
        {
            $collectionOuput = 'show_product_thumbnail';
        }   
        $selectedColumn = array('*');
    
        $get = $this->productInterface->show($request,$selectedColumn,$collectionOuput);
   
        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get product success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-product',
            'message'=> 'error when show product'
        ]);

        return  $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);

    }
}
