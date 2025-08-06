<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ProductCategoryResource\ProductCategoryShowAllResource;
use App\Interfaces\ProductCategoryInterface;
use App\Models\Product_categories;
use App\PipelineFilters\ProductCategoryPipeline\GetByKey;
use App\PipelineFilters\ProductCategoryPipeline\GetByWord;
use App\PipelineFilters\ProductCategoryPipeline\UseSort;
use Illuminate\Pipeline\Pipeline;

class ProductCategoryRepository extends BaseController implements ProductCategoryInterface 
{
    public function show($request,$getOnlyColumn) {

        try {
            $getData =  app(Pipeline::class)
                                    ->send(Product_categories::query())
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

                                    $message =   'get product category with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1 )
                                    {
                                        $message =   'get product category data success without pagination max 250 data';
                                    }
                                    else
                                    {
                                        $message =   'get product category data success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get product category');
        }

      
    }
    public function upsert($data) {

        try {   
         
            foreach($data['data'] as $datas ) {
           
                if($datas['id'] !== null) {
                    
                   $upsertData =  Product_categories::find($datas['id']);
    
                   if($upsertData) {
    
                    $upsertData->update($datas);
                   }
                   else {
    
                    $upsertData = Product_categories::create($datas);
                   }
                }
                else {
    
                    $upsertData = Product_categories::create($datas);
                }
    
            }

            $message     =   'upsert success';
            $outputData  = count($data['data']);

            return $this->handleQueryArrayResponse($outputData,$message);
            
            } catch (\Exception $e) {
            
                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when insert / update product category');
            }

    }
    public function destroy(int $id){
       
        try {
            $remove =  Product_categories::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy product category success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy product category fail');
            }
        } 
        catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product category');
        }

    }
    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new ProductCategoryShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }
       
    }
   
}