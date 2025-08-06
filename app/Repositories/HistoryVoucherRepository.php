<?php
namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\HistoryVoucherResource\HistoryVoucherShowAllResource;
use App\Interfaces\HistoryVoucherInterface;
use App\Models\HistoryVoucher;
use Illuminate\Pipeline\Pipeline;
use App\PipelineFilters\HistoryVoucherPipeline\GetByKey;
use App\PipelineFilters\HistoryVoucherPipeline\GetByWord;
use App\PipelineFilters\HistoryVoucherPipeline\UseSort;
use Illuminate\Http\Request;

class HistoryVoucherRepository extends BaseController implements HistoryVoucherInterface 
{
    public function show($request,$getOnlyColumn)
    {
    
        // try {
                $getData =  app(Pipeline::class)
                                ->send(HistoryVoucher::query())
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

                                $message =   'get voucher with paginate success';
                            }
                            else {
                            
                                $outputData =  $itemsTransformed;
                                if(count($getCollection) > 1 )
                                {
                                    $message =   'get voucher data success without pagination max 250 data';
                                }
                                else
                                {
                                    $message =   'get voucher data success';
                                }
                                
                            }

                return $this->handleQueryArrayResponse($outputData,$message);

        // } catch (\Exception $e) {

        //     return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get history voucher');
        // }
    }


    public function store($data,$returnColumn) {
    
        try {
            $create = HistoryVoucher::create($data);
            
            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnColumn,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert history voucher Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert voucher fail');
            }
    
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store history voucher');
        }
    
    }
    public function update($id,$data,$returnColumn) {

        try {
            $update  =  HistoryVoucher::find($id);

            if($update) {

            $update->update($data);
            
            $refotmatData =  $this->resourceFormat($returnColumn,$update);

            return $this->handleQueryArrayResponse($refotmatData,'update history voucher success');

            } else {

                return $this->handleQueryErrorArrayResponse($update,'updates fail - history voucher id not found');
                
            }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update voucher');
        }
    }
    public function destroy($id) {

        try {
            $remove =  HistoryVoucher::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy voucher success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy voucher fail');
            }

        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product');
        }
    } 

    private  function resourceFormat($returnCollection,$data) {

        return new HistoryVoucherShowAllResource([
            'data' => $data,
            'status' => true
        ]);
    }

}