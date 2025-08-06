<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Blog\BlogShowAllResource;
use App\Interfaces\BlogInterface;
use App\Models\Blog;
use Illuminate\Pipeline\Pipeline;
use App\PipelineFilters\BlogPipeline\GetByKey;
use App\PipelineFilters\BlogPipeline\GetByWord;
use App\PipelineFilters\BlogPipeline\UseSort;

class BlogRepository extends BaseController implements BlogInterface 
{
    public function show($request,$getOnlyColumn,$collection='show_all') {
       
        try {
          
            $getData =  app(Pipeline::class)
                                ->send(Blog::query())
                                ->through([
                                    GetByKey::class,
                                    GetByWord::class,
                                    UseSort::class,
                                ])
                                ->thenReturn();

                            
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

                                $message =   'get blog with paginate success';
                            }
                            else {
                            
                                $outputData =  $itemsTransformed;
                                if(count($getCollection) > 1 )
                                {
                                    $message =   'get blog data success without pagination max 250 data';
                                }
                                else
                                {
                                    $message =   'get blog data success';
                                }
                                
                        }

                        if (request()->get('slug')) {
                            $blog = Blog::where('slug', request()->get('slug'))->first();
                    
                            if (!$blog) {
                                return $this->handleError([], 'Blog not found', request()->all(), \Request::path(), 404);
                            }

                            $recommendedBlogs = Blog::where('fk_category',$blog->fk_category)
                                ->where('id', '<>', $blog->id)
                                ->limit(3) 
                                ->get();
                
                    
                            $recommendedTransformed = $recommendedBlogs->map(function ($item) use ($collection) {
                                return $this->resourceFormat($collection, $item);
                            });

                            $combinedData = [
                                'main_blog' => $outputData,
                                'recommended_blogs' => $recommendedTransformed
                            ];

                            return $this->handleQueryArrayResponse($combinedData, $message);
                        }

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get blog');
        
        }
           
    }
    public function store($data,$returnColumn) {

        try {
            $create = Blog::create($data);

            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnColumn,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert blog Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert blog fail');
            }
      
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store blog');
        }
     

    }
    public function update($id,$data,$returnColumn) {
        
        try {
            $update  =  Blog::find($id);

            if($update) {

            $update->update($data);
            
            $refotmatData =  $this->resourceFormat($returnColumn,$update);

            return $this->handleQueryArrayResponse($refotmatData,'update blog success');

            } else {

                return $this->handleQueryErrorArrayResponse($update,'updates fail - blog id not found');
                
            }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update blog');
        }
      

    }
    public function destroy($id) {

        try {
            $remove =  Blog::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy blog success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy blog fail');
            }

        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory blog');
        }
    }

    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new BlogShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }
       
       
    }

    public function showWithRecommendations($request, $getOnlyColumn, $collection = 'show_all') {
        try {
            $blog = Blog::where('slug', $request->get('slug'))->first();

            if (!$blog) {
                return $this->handleError([], 'Blog not found', $request->all(), Request::path(), 404);
            }

            $category_id = $blog->fk_category->taxonomy_slug;

            $getData = app(Pipeline::class)
                ->send(Blog::query())
                ->through([
                    GetByWord::class,
                    UseSort::class,
                ])
                ->thenReturn();

            $getData->where('id', '!=', $blog->id)
                ->whereHas('fk_category', function ($query) use ($category_id) {
                    $query->where('taxonomy_slug', $category_id);
                });

            if ($request->get('paginate') == true) {
                $outputData = $getData->paginate($request->get('per_page'), $getOnlyColumn, 'page', $request->get('page'));
                $getCollection = $outputData->getCollection();
            } else {
                $getCollection = $getData->limit(250)->get();
            }

            $itemsTransformed = $getCollection
                ->map(function ($item) use ($collection) {
                    return $this->resourceFormat($collection, $item);
                });

            if (count($getCollection) > 1 || $request->get('paginate') == true) {
                $itemsTransformed = $itemsTransformed->toArray();
            } else {
                $itemsTransformed = $itemsTransformed->first();
            }

            // Combine the main blog with recommendations
            $combinedData = [
                'main_blog' => $this->resourceFormat($collection, $blog),
                'recommended_blogs' => $itemsTransformed
            ];

            return $this->handleQueryArrayResponse($combinedData, 'get main blog and recommended blogs success');

        } catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(), 'error when get main blog and recommended blogs');
        }
    }

   
}