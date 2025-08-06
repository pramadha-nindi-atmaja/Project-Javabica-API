<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ProductImageResource\ProductImageShowAllResource;
use App\Interfaces\ProductImageInterface;
use App\Models\Product_image;
use App\PipelineFilters\ProductImagePipeline\GetByKey;
use App\PipelineFilters\ProductImagePipeline\GetByWord;
use App\PipelineFilters\ProductImagePipeline\UseSort;
use Illuminate\Pipeline\Pipeline;
class ProductImageRepository extends BaseController implements ProductImageInterface 
{
    public function show($request,$getOnlyColumn) 
    {
        try {
            $getData =  app(Pipeline::class)
                                    ->send(Product_image::query())
                                    ->through([
                                        GetByKey::class,
                                        GetByWord::class,
                                        UseSort::class,
                                    ])
                                    ->thenReturn()
                                    ->select($getOnlyColumn);

                                    if(request()->get('paginate') == true)
                                    {
                                        $outputData            =  $getData->paginate(request()->get('per_page') ,$getOnlyColumn,'page',request()->get('page'));
                                        $getCollection         =  $outputData->getCollection();
                                    }
                                    else
                                    {   
                                        $getCollection  =   $getData->limit(250)->get();
                                    }
                                    $itemsTransformed = $getCollection
                                    ->map(function($item) { 

                                        return $this->resourceFormat('show_all',$item);
                                       
                                    });


                                    if(count($getCollection) > 1 || request()->get('paginate') == true)
                                    {
                                        
                                        $itemsTransformed =  $itemsTransformed->toArray();
                                    }
                                    else
                                    {
                                        $itemsTransformed =  $itemsTransformed->first();
                                    }
                                    
                                    
                                if(request()->get('paginate') == true)
                                {
                                    $outputData = new \Illuminate\Pagination\LengthAwarePaginator(
                                        $itemsTransformed,
                                        $outputData->total(),
                                        $outputData->perPage(),
                                        $outputData->currentPage(), [
                                            'path' => \Request::url(),
                                            'query' =>request()->all()
                                        ]
                                    );

                                    $message =   'get product images with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1 )
                                    {
                                        $message =   'get product images data success without pagination max 250 data';
                                    }
                                    else
                                    {
                                        $message =   'get product images data success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get product images');
        }
    }
   

    public function upsert($data) {

        try {   
         
            foreach($data['data'] as $datas ) {
           
                if($datas['id'] !== null) {
                    
                   $upsertData =  Product_image::find($datas['id']);
    
                   if($upsertData) {
    
                    $upsertData->update($datas);
                   }
                   else {
    
                    $upsertData = Product_image::create($datas);
                   }
                }
                else {
    
                    $upsertData = Product_image::create($datas);
                }
    
            }

            $message     =   'upsert success';
            $outputData  = count($data['data']);

            return $this->handleQueryArrayResponse($outputData,$message);
            
            } catch (\Exception $e) {
            
                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when insert / update product image');
            }

            
    }
    public function destroy(int $id){

        try {
            $remove =  Product_image::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy product image success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy product image fail');
            }
        } 
        catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product image');
        }

    }
    private  function resourceFormat($returnCollection ='show_all',$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new ProductImageShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }

    }
   
}