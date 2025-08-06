<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ProductResource\ProductShowAllResource;
use App\Http\Resources\ProductResource\ProductShowDetailResource;
use App\Http\Resources\ProductResource\ProductShowThumbnailResource;
use App\Interfaces\ProductInterface;
use App\Models\Product;
use App\Models\Product_variant;
use App\PipelineFilters\MasterProductPipeline\GetByKey;
use App\PipelineFilters\MasterProductPipeline\GetByWord;
use App\PipelineFilters\MasterProductPipeline\UseSort;
use Illuminate\Pipeline\Pipeline;

class ProductRepository extends BaseController implements ProductInterface 
{
    public function show($request,$getOnlyColumn,$collection = 'show_all') {

        try {
         // return   Product_variant::orderBy('price','desc')->get();
          
            $getData =  app(Pipeline::class)
                                    ->send(Product::query())
                                    ->through([
                                        GetByKey::class,
                                        GetByWord::class,
                                        UseSort::class,
                                    ])
                                    ->thenReturn();
                                    if($collection == 'show_product_detail')
                                    {
                                        // show_product_detail  karena di pake untuk area public jadi di paksa yang aktif aja
                                        $getData->where('products.status','PUBLISH');
                                    }
                                    if($collection == 'show_product_thumbnail')
                                    {
                                        
                                        // show_product_thumbnail karena di pake untuk area public jadi di paksa yang aktif aja
                                        $getData->select([
                                            'products.*', 
                                            'product_variants.price',
                                            'product_variants.discount',
                                            'product_variants.fk_product_id'
                                            ])->where('products.status','PUBLISH')->join('product_variants', 'products.id', '=', 'product_variants.fk_product_id');
                                          
                                          
                                      if(request()->has('sort_by') && request()->get('sort_by') == 'product_price')
                                       { 
                                           $getData->orderBy('price',request()->get('sort_type'));
                                       }
                                       if(request()->has('sort_by') && request()->get('sort_by') == 'product_discount')
                                       {
                                           $getData->orderBy('discount',request()->get('sort_type'));
                                       }
                                       if(request()->has('sort_by') && request()->get('sort_by') == 'name')
                                       {
                                           $getData->orderBy('products.name',request()->get('sort_type'));
                                       }
                                       else
                                       {
                                             $getData->orderBy('sort','asc');
                                       }
                                    }
                                    else
                                    {
                                        $getData->select($getOnlyColumn);
                                    }

                                    if(request()->has('get_random') &&  request()->get('get_random') == true)
                                    {
                                        $getData->inRandomOrder();
                                    }
                                      
                                    if(request()->get('paginate') == true)
                                    {
                                        $outputData            =  $getData->paginate(request()->get('per_page') ,$getOnlyColumn,'page',request()->get('page'));
                                        $getCollection         =  $outputData->getCollection();
                                    }
                                    else
                                    {   
                                        $getCollection  =   $getData->limit(250)->get();
                                    }
                                    if($collection == 'show_product_thumbnail')
                                    {
                                        $getCollection =  $getCollection->unique('id');
                                    }
                                  
                                    $itemsTransformed = $getCollection
                                    ->map(function($item) use($collection)  { 
                                        return $this->resourceFormat($collection,$item);
                                       
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

                                    $message =   'get product with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1 )
                                    {
                                        $message =   'get product data success without pagination max 250 data';
                                    }
                                    else
                                    {
                                        $message =   'get product data success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get products');
        }
        
    }
    public function store($data,$returnCollection ='show_all') {
        
        try {
            $create = Product::create($data);

            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnCollection,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert product Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert product fail');
            }
      
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store product');
        }


    }
    public function update($id,$data,$returnCollection='show_all') {
        
        
        try {
            $update  =  Product::find($id);

            if($update) {

            $update->update($data);
            
            $refotmatData =  $this->resourceFormat($returnCollection,$update);

            return $this->handleQueryArrayResponse($refotmatData,'update product success');

            } else {

                return $this->handleQueryErrorArrayResponse($update,'updates fail - product id not found');
                
            }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update product');
        }


    }
    public function destroy($id) {

        try {
            $remove =  Product::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy product success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy product fail');
            }

        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product');
        }

    }
    
    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new ProductShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }
        if($returnCollection == 'show_product_detail') //faq service & experience
        {   
            return new ProductShowDetailResource([
                'data' => $data,
                'status' => true
            ]);
        }
        if($returnCollection == 'show_product_thumbnail') //faq service & experience
        {   
            
            return new ProductShowThumbnailResource([
                'data' => $data,
                'status' => true
            ]);
        }
       
    }
   
}