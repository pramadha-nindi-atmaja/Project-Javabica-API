<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ProductVariantResource\ProductVariantShowAllResource;
use App\Interfaces\ProductVariantInterface;
use App\Models\Product_variant;
use App\PipelineFilters\ProductVariantPipeline\GetByKey;
use App\PipelineFilters\ProductVariantPipeline\GetByWord;
use App\PipelineFilters\ProductVariantPipeline\UseSort;
use Illuminate\Pipeline\Pipeline;

class ProductVariantRepository extends BaseController implements ProductVariantInterface 
{
    public function show($request,$getOnlyColumn) {

        try {
            $getData =  app(Pipeline::class)
                                    ->send(Product_variant::query())
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

                                    $message =   'get product variant with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1 )
                                    {
                                        $message =   'get product variant data success without pagination max 250 data';
                                    }
                                    else
                                    {
                                        $message =   'get product variant data success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get product variant');
        }
      
    }
    public function upsert($data) {

        try {   
         
            foreach($data['data'] as $datas ) {
           
                if($datas['id'] !== null) {
                    
                   $upsertData =  Product_variant::find($datas['id']);
    
                   if($upsertData) {
    
                    $upsertData->update($datas);
                   }
                   else {
    
                    $upsertData = Product_variant::create($datas);
                   }
                }
                else {
    
                    $upsertData = Product_variant::create($datas);
                }
    
            }

            $message     =   'upsert success';
            $outputData  = count($data['data']);

            return $this->handleQueryArrayResponse($outputData,$message);
            
            } catch (\Exception $e) {
            
                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when insert / update product variant');
            }

    }
    public function destroy($data){
       
        try {
            if(isset($data['by_id']))
            {
                $remove =  Product_variant::where('id',$data['by_id'])->delete();
            }
            if(isset($data['by_product_id']))
            {
                $remove =  Product_variant::where('fk_product_id',$data['by_product_id'])->delete();
            }

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy product variant success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy product variant fail');
            }
        } 
        catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product variant');
        }

    }
    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new ProductVariantShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }

       
    }
   
}