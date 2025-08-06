<?php

namespace App\Repositories;
use App\Interfaces\TaxonomyInterface;
use App\Http\Controllers\BaseController;
use App\Http\Resources\TaxonomyResource\CategoryCollectionResource;
use App\Http\Resources\TaxonomyResource\ProductCollectionResource;
use App\Http\Resources\TaxonomyResource\TaxonomyShowAllResource;
use App\Models\Taxo_list;
use App\Models\Taxo_type;
use App\Models\Product_categories;
use App\Models\Product_collection;
use App\PipelineFilters\TaxonomyPipeline\GetByKey;
use App\PipelineFilters\TaxonomyPipeline\GetByWord;
use App\PipelineFilters\TaxonomyPipeline\UseSort;
use Illuminate\Pipeline\Pipeline;

//use Your Model

/**
 * Class TaxonomyRepository.
 */
class TaxonomyRepository extends BaseController implements TaxonomyInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function show($request,$getOnlyColumn)
    {
      
        try {
                $getData =  app(Pipeline::class)
                                        ->send(Taxo_list::query())
                                        ->through([
                                            GetByKey::class,
                                            GetByWord::class,
                                            UseSort::class,
                                        ])
                                        ->thenReturn()
                                        ->select($getOnlyColumn);

                                        if(request()->get('paginate') == true)
                                        {
                                            $outputData            =  $getData->paginate(request()->get('per_page') ,$getOnlyColumn,'page',request()->get('currentPage'));
                                            $getCollection         =  $outputData->getCollection();
                                        }
                                        else
                                        {   
                                            $getCollection  =   $getData->limit(250)->get();
                                        }
                                        $itemsTransformed = $getCollection
                                        ->map(function($item) use($request) { 

                                            return $this->resourceFormat($request->get('by_taxo_type_id'),$item);
                                           
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

                                        $message =   'get taxonomy with paginate success';
                                    }
                                    else {
                                    
                                        $outputData =  $itemsTransformed;
                                        if(count($getCollection) > 1 )
                                        {
                                            $message =   'get taxonomy data success without pagination max 250 data';
                                        }
                                        else
                                        {
                                            $message =   'get taxonomy data success';
                                        }
                                        
                                    }
                                    

                return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {
    
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get taxonomy');
        }
    }
    public function show_type() {

        $getData =  Taxo_type::select('*')->get();

        return $this->handleQueryArrayResponse($getData,'get taxonomy type success');

    }
    public function store(array $data,$returnCollection='showAll')
    {  
        try {
                $taxo = Taxo_list::create($data);

                if($taxo) {
                    
                    $reformatUpdate =  $this->resourceFormat($returnCollection,$taxo);
                    return $this->handleQueryArrayResponse($reformatUpdate,'insert taxo Success');
        
                } else {
        
                    return $this->handleQueryErrorArrayResponse($taxo,'insert taxo fail');
                }
          
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store taxonomy');
        }

    }
    public function update($id,array $data,$returnCollection='showAll')
    {  
        try {
                $update  =  Taxo_list::find($id);

                if($update) {

                $update->update($data);
                
                $refotmatData =  $this->resourceFormat($returnCollection,$update);

                return $this->handleQueryArrayResponse($refotmatData,'update taxonomy success');

                } else {

                    return $this->handleQueryErrorArrayResponse($update,'updates fail - taxonomy id not found');
                    
                }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update taxonomy');
        }
    }
    public function destroy($id)
    {
        try {
                $remove =  Taxo_list::where('id',$id)->first();
                $productCategory1 = Product_categories::where('fk_category_id', $remove->id)->exists();
                $productCollection = Product_collection::where('fk_collection_id', $remove->id)->exists();

                if($remove->parent != '' || $remove->parent != NULL){
                    $productCategory2 = Product_categories::where('fk_category_id', $remove->parent)->exists();
                    if($productCategory1 && $productCategory2){
                        
                        return $this->handleQueryErrorArrayResponse($productCategory2,'Failed to delete subcategory because it is still used in the product');
                    }
                }

                if($productCategory1 && !$productCollection){
                    return $this->handleQueryErrorArrayResponse($productCategory1,'Failed to delete category because it is still used in the product');
                }
                elseif(!$productCategory1 && $productCollection){
                    return $this->handleQueryErrorArrayResponse($productCollection,'Failed to delete collection because it is still used in the product');
                }
                else
                {
                    if($remove == true)
                    {
                        $remove->delete();
                        return $this->handleQueryArrayResponse($remove,'destroy taxonomy success');
                    }
                    else
                    {
                        return $this->handleQueryErrorArrayResponse($remove,'destroy taxonomy fail');
                    }
                }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory taxonomy');
        }
    }
    private function resourceFormat($formatType,$data) {
      
        if($formatType == 1) 
        {
            
            return new ProductCollectionResource([
                'data' => $data,
                'status' => true
            ]);
        }
        if($formatType == 2) 
        {
        
            return new CategoryCollectionResource([
                'data' => $data,
                'status' => true
            ]);
        }
        else {

            return new TaxonomyShowAllResource([
                'data' => $data,
                'status' => true
            ]);

        }
    }
   
}
