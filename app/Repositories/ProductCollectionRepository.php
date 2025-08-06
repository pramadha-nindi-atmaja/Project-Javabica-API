<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ProductCollectionResource\ProductCollectionShowAllResource;
use App\Interfaces\ProductCollectionInterface;
use App\Models\Product_collection;
use App\PipelineFilters\ProductCollectionPipeline\GetByKey;
use App\PipelineFilters\ProductCollectionPipeline\GetByWord;
use App\PipelineFilters\ProductCollectionPipeline\UseSort;
use Illuminate\Pipeline\Pipeline;

class ProductCollectionRepository extends BaseController implements ProductCollectionInterface 
{
    public function show($request,$getOnlyColumn) {

        try {
            $getData =  app(Pipeline::class)
                                    ->send(Product_collection::query())
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

                                    $message =   'get product collection with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1 )
                                    {
                                        $message =   'get product collection data success without pagination max 250 data';
                                    }
                                    else
                                    {
                                        $message =   'get product collection data success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get product collection');
        }

    }
    public function upsert($data) {

        try {   
         
            foreach($data['data'] as $datas ) {
           
                if($datas['id'] !== null) {
                    
                   $upsertData =  Product_collection::find($datas['id']);
    
                   if($upsertData) {
    
                    $upsertData->update($datas);
                   }
                   else {
    
                    $upsertData = Product_collection::create($datas);
                   }
                }
                else {
    
                    $upsertData = Product_collection::create($datas);
                }
    
            }

            $message     =   'upsert success';
            $outputData  = count($data['data']);

            return $this->handleQueryArrayResponse($outputData,$message);
            
            } catch (\Exception $e) {
            
                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when insert / update product collection');
            }

            
    }
    public function destroy(int $id){

        try {
            $remove =  Product_collection::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy product collection success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy product collection fail');
            }
        } 
        catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product collection');
        }

    }
    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new ProductCollectionShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }
       
    }
   
}