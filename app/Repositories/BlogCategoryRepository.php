<?php
namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CategoryBlog\CategoryBlogShowAllResource;
use App\Interfaces\BlogCategoryInterface;
use App\Models\CategoryBlog;
use Illuminate\Pipeline\Pipeline;
use App\PipelineFilters\BlogCategoryPipeline\GetByKey;
use App\PipelineFilters\BlogCategoryPipeline\GetByWord;
use App\PipelineFilters\BlogCategoryPipeline\UseSort;
use Illuminate\Http\Request;

class BlogCategoryRepository extends BaseController implements BlogCategoryInterface 
{
    public function show($request,$getOnlyColumn)
    {
      
        try {
                $getData =  app(Pipeline::class)
                                ->send(CategoryBlog::query())
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
                                
                                $itemsTransformed = $getCollection->map(function($item) use($request) { 
                                    return $this->resourceFormat($request,$item);
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

                                $message =   'get blog category with paginate success';
                            }
                            else {
                            
                                $outputData =  $itemsTransformed;
                                if(count($getCollection) > 1 )
                                {
                                    $message =   'get blog category data success without pagination max 250 data';
                                }
                                else
                                {
                                    $message =   'get blog category data success';
                                }
                                
                            }

                
                                    

                return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get taxonomy');
        }
    }


    public function store($data,$returnColumn) {
       
        try {
            $create = CategoryBlog::create($data);
            
            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnColumn,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert blog category Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert blog category fail');
            }
      
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store blog category');
        }
     
    }
    public function update($id,$data,$returnColumn) {

        try {
            $update  =  CategoryBlog::find($id);

            if($update) {

            $update->update($data);
            
            $refotmatData =  $this->resourceFormat($returnColumn,$update);

            return $this->handleQueryArrayResponse($refotmatData,'update blog category success');

            } else {

                return $this->handleQueryErrorArrayResponse($update,'updates fail - blog category id not found');
                
            }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update blog');
        }
    }
    public function destroy($id) {

     
        try {
            $remove =  CategoryBlog::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy blog category success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy blog category fail');
            }

        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product');
        }
    } 

    private  function resourceFormat($returnCollection,$data) {

        return new CategoryBlogShowAllResource([
            'data' => $data,
            'status' => true
        ]);
    }

}