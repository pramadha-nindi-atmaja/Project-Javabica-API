<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\LocationStoreResource\LocationShowAllResource;
use App\Interfaces\LocationStoreInterface;
use App\Models\Location_stores;
use App\PipelineFilters\LocationStorePipeline\FilterLocationStoreQueryPipeline;
use Illuminate\Pipeline\Pipeline;




class LocationStoreRepository  extends BaseController implements LocationStoreInterface 
{
    public function show($request,$getOnlyColumn,$returnCollection) {

        try {
               
            $getData =  app(Pipeline::class)
                                    ->send($request)
                                    ->through([  
                                        FilterLocationStoreQueryPipeline::class,
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
                                    ->map(function($item) use ($returnCollection) { 
                                      
                                        return $this->resourceFormat($returnCollection,$item);
                                    
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

                                    $message =   'get location store with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1 )
                                    {
                                        $message =   'get location store  success without pagination max 250 ';
                                    }
                                    else
                                    {
                                        $message =   'get location store  success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get location store');
        }
        
    }
    public function store($data,$returnColumn) {

        try {
            $create = Location_stores::create($data);

            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnColumn,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert location Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert location fail');
            }
      
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store location');
        }

    }
    public function update($id,$data,$returnColumn) {
        
        try {
            $update  =  Location_stores::find($id);

            if ($update) {

                $update->update($data);

                $refotmatData =  $this->resourceFormat($returnColumn, $update);

                return $this->handleQueryArrayResponse($refotmatData, 'update location store success');
            } else {

                return $this->handleQueryErrorArrayResponse($update, 'updates fail - location store id not found');
            }
        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(), 'error when update location store');
        }

    }
    public function destroy($id) {

        try {
            $remove =  Location_stores::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy location store success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy location store fail');
            }

        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory location store');
        }
    }
    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new LocationShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }
       
       
    }
   
}