<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ProductPriceResource\ProductPriceShowAllResource;
use App\Interfaces\ProductPriceInterface;
use App\Models\Product_price;
use App\PipelineFilters\ProductPricePipeline\GetByKey;
use App\PipelineFilters\ProductPricePipeline\GetByWord;
use App\PipelineFilters\ProductPricePipeline\UseSort;
use Illuminate\Pipeline\Pipeline;

class  ProductPriceRepository extends BaseController implements ProductPriceInterface 
{
    public function show($request,$getOnlyColumn) {

        try {
            $getData =  app(Pipeline::class)
                                    ->send(Product_price::query())
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

                                    $message =   'get product prices with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1 )
                                    {
                                        $message =   'get product prices data success without pagination max 250 data';
                                    }
                                    else
                                    {
                                        $message =   'get product prices data success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get product prices');
        }


    }
    public function upsert($data) {

        try {   
         
            foreach($data['data'] as $datas ) {
           
                if($datas['id'] !== null) {
                    
                   $upsertData =  Product_price::find($datas['id']);
    
                   if($upsertData) {
    
                    $upsertData->update($datas);
                   }
                   else {
    
                    $upsertData = Product_price::create($datas);
                   }
                }
                else {
    
                    $upsertData = Product_price::create($datas);
                }
    
            }

            $message     =   'upsert success';
            $outputData  = count($data['data']);

            return $this->handleQueryArrayResponse($outputData,$message);
            
            } catch (\Exception $e) {
            
                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when insert / update product price');
            }
       
            
    }
    public function destroy(int $id){

        try {
            $remove =  Product_price::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy product price success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy product price fail');
            }
        } 
        catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product price');
        }

       

    }
    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') 
        {
            
            return new ProductPriceShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }
     
       
    }
   
}