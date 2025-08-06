<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\OrderResource\OrderShowAllResource;
use App\Http\Resources\OrderResource\OrderWithProductResource;
use App\Interfaces\OrderInterface;
use App\Models\Order;
use App\PipelineFilters\OrderPipeline\FilterQueryPipeline;
use Illuminate\Pipeline\Pipeline;
class OrderRepository extends BaseController implements OrderInterface 
{
    public function show($request,$getOnlyColumn,$returnCollection = 'show_all') {

            try {
               
                $getData =  app(Pipeline::class)
                                        ->send($request)
                                        ->through([  
                                            FilterQueryPipeline::class,
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

                                        $message =   'get order with paginate success';
                                    }
                                    else {
                                    
                                        $outputData =  $itemsTransformed;
                                        if(count($getCollection) > 1 )
                                        {
                                            $message =   'get order data success without pagination max 250 data';
                                        }
                                        else
                                        {
                                            $message =   'get order data success';
                                        }
                                        
                                    }
                                    

                return $this->handleQueryArrayResponse($outputData,$message);

            } catch (\Exception $e) {

                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get order');
            }
    }
    public function store($data,$returnCollection) {

        try {
            $create = Order::create($data);

            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnCollection,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert order Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert order fail');
            }
      
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store order');
        }

    }
    public function update($id,$data,$returnCollection) {
        
        try {
            $update  =  Order::find($id);

            if($update) {

            $update->update($data);
            
            $refotmatData =  $this->resourceFormat($returnCollection,$update);

            return $this->handleQueryArrayResponse($refotmatData,'update order success');

            } else {

                return $this->handleQueryErrorArrayResponse($update,'updates fail - order id not found');
                
            }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update order');
        }

    }
    public function destroy($id) {

        try {
            $remove =  Order::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy order success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy order fail');
            }

        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory order');
        }
       
    }
    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new OrderShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }
        if($returnCollection == 'show_with_product') //faq service & experience
        {   
            return new OrderWithProductResource([
                'data' => $data,
                'status' => true
            ]);
        }
       
    }
   
}