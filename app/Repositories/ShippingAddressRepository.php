<?php


namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserShippingAddress\ShippingAddressShowAllResource;
use App\Interfaces\ShippingAddressInterface;

use App\Models\User_shipping_address;
use App\PipelineFilters\ShippingAddressPipeline\GetByKey;
use App\PipelineFilters\ShippingAddressPipeline\GetByWord;
use App\PipelineFilters\ShippingAddressPipeline\UseSort;
use Illuminate\Pipeline\Pipeline;

class ShippingAddressRepository  extends BaseController implements ShippingAddressInterface 
{
    public function show($request,$getOnlyColumn,$returnColumn='show_all') {

        try {
            $getData =  app(Pipeline::class)
                                    ->send(User_shipping_address::query())
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
                                    ->map(function($item) use($returnColumn) { 

                                        return $this->resourceFormat($returnColumn,$item);
                                       
                                    });


                                    if(count($getCollection) > 1 || request()->get('paginate') == true)
                                    {
                                        
                                        $itemsTransformed =  $itemsTransformed->toArray();
                                    }
                                    else
                                    {
                                        // nanda request apabila datanya 1 tetep  outputnya bentuk array, request date 7 november 2022
                                        $itemsTransformed =  $itemsTransformed->toArray(); 
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

                                    $message =   'get shipping address with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1 )
                                    {
                                        $message =   'get shipping address data success without pagination max 250 data';
                                    }
                                    else
                                    {
                                        $message =   'get shipping address data success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get shipping address');
        }
        
        
    }
    public function store($data,$returnColumn) {

        try {
            $create = User_shipping_address::create($data);

            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnColumn,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert shipping address Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert shipping address fail');
            }
      
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store shipping address');
        }

    }
    public function update($id,$data,$returnColumn) {
        
        try {
            $update  =  User_shipping_address::find($id);

            if($update) {

            $update->update($data);
            
            $refotmatData =  $this->resourceFormat($returnColumn,$update);

            return $this->handleQueryArrayResponse($refotmatData,'update shipping address success');

            } else {

                return $this->handleQueryErrorArrayResponse($update,'updates fail - shipping address id not found');
                
            }
        } 
        catch (\Exception $e) {
            
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update shipping address');
        }

    }
    public function destroy($id) {

        try {
            $remove =  User_shipping_address::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy shipping address success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy shipping address fail');
            }

        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory shipping address');
        }
       
    }
    private  function resourceFormat($returnCollection,$data) {

        if($returnCollection == 'show_all') //faq service & experience
        {   
            return new ShippingAddressShowAllResource([
                'data' => $data,
                'status' => true
            ]);
        }

    }
}