<?php
namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\VoucherResource\VoucherShowAllResource;
use App\Interfaces\VoucherInterface;
use App\Models\Voucher;
use App\Models\HistoryVoucher;
use Illuminate\Pipeline\Pipeline;
use App\PipelineFilters\VoucherPipeline\GetByKey;
use App\PipelineFilters\VoucherPipeline\GetByWord;
use App\PipelineFilters\VoucherPipeline\UseSort;
use Illuminate\Http\Request;

class VoucherRepository extends BaseController implements VoucherInterface 
{
    public function show($request,$getOnlyColumn)
    {
    
        try {
                $getData =  app(Pipeline::class)
                                ->send(Voucher::query())
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

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get voucher');
        }
    }


    public function store($data,$returnColumn) {
    
        try {
            $create = Voucher::create($data);
            
            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnColumn,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert voucher Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert voucher fail');
            }
    
        } catch (\Exception $e) {
        
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store voucher');
        }
    
    }
    public function update($id,$data,$returnColumn) {

        try {
            $update  =  Voucher::find($id);

            if($update) {

            $update->update($data);
            
            $refotmatData =  $this->resourceFormat($returnColumn,$update);

            return $this->handleQueryArrayResponse($refotmatData,'update voucher success');

            } else {

                return $this->handleQueryErrorArrayResponse($update,'updates fail - voucher id not found');
                
            }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update voucher');
        }
    }
    public function destroy($id) {

        try {
            $remove =  Voucher::where('id',$id)->delete();

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

        return new VoucherShowAllResource([
            'data' => $data,
            'status' => true
        ]);
    }

    public function check_voucher($request,$getOnlyColumn)
    {
        $voucher =  Voucher::where('code',$request->keyword)->first();
        if($voucher != null){
            $historyVoucher =  HistoryVoucher::where('voucher_id', $voucher->id)->count();
            $historyUserVoucher =  HistoryVoucher::where('voucher_id', $voucher->id)->where('user_id', auth()->user()->id)->count();
            
            if($voucher->total != NULL && $historyVoucher >= $voucher->total){
                $message = 'Voucher has reach limit usage';
                return $this->handleQueryErrorArrayResponse($message);
            }
            elseif($voucher->max_usage != NULL && $historyUserVoucher >= $voucher->max_usage){
                $message = 'You have already used this voucher';
                return $this->handleQueryErrorArrayResponse($message);
            }
            elseif($voucher->min_payment != NULL && $request->total < $voucher->min_payment){
                $message = 'Total payment does not meet the minimum payment requirements';
                return $this->handleQueryErrorArrayResponse($message);
            }
            elseif($voucher->start_date != NULL && $voucher->start_date > now()){
                $message = 'Voucher not open yet';
                return $this->handleQueryErrorArrayResponse($message);
            }
            elseif($voucher->end_date != NULL && $voucher->end_date < now()){
                $message = 'Voucher has expired';
                return $this->handleQueryErrorArrayResponse($message);
            }
            elseif($voucher->is_active == 1){
                $message = 'Voucher successfully used';
                return $this->handleQueryArrayResponse($voucher,$message);
            }
            else{
                $message = 'Voucher is not active';
                return $this->handleQueryErrorArrayResponse($message);
            }
        }
        else
        {
            $message = 'Voucher with this code not found';
            return $this->handleQueryErrorArrayResponse($message);
        }
    }
}