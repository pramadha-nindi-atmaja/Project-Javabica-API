<?php
namespace App\Repositories;

use App\Http\Controllers\BaseController;
use App\Http\Resources\MachineResource\MachineShowAllResource;
use App\Interfaces\MachineInterface;
use App\Models\Machine;
use App\Models\User;
use Illuminate\Pipeline\Pipeline;
use App\PipelineFilters\MachinePipeline\GetByKey;
use App\PipelineFilters\MachinePipeline\GetByWord;
use App\PipelineFilters\MachinePipeline\UseSort;
use Illuminate\Http\Request;

class MachineRepository extends BaseController implements MachineInterface 
{
    public function show($request,$getOnlyColumn)
    {
        try {
                $getData =  app(Pipeline::class)
                                ->send(Machine::query())
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

                                $message =   'get machine with paginate success';
                            }
                            else {
                            
                                $outputData =  $itemsTransformed;
                                if(count($getCollection) > 1 )
                                {
                                    $message =   'get machine data success without pagination max 250 data';
                                }
                                else
                                {
                                    $message =   'get machine data success';
                                }
                                
                            }

                
                                    

                return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get taxonomy');
        }
    }

    public function show_all_machine($request,$getOnlyColumn)
    {
        try {
                $getData =  app(Pipeline::class)
                                ->send(Machine::query())
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

                                $message =   'get machine with paginate success';
                            }
                            else {
                            
                                $outputData =  $itemsTransformed;
                                if(count($getCollection) > 1 )
                                {
                                    $message =   'get machine data success without pagination max 250 data';
                                }
                                else
                                {
                                    $message =   'get machine data success';
                                }
                                
                            }

                
                                    

                return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get taxonomy');
        }
    }

    public function store($data,$returnColumn) {
        // try {
            $user = User::where('email', $data['by_email'])->first();
            $data['user_id'] = $user->id;
            $create = Machine::create($data);
            
            if($create) {
                
                $reformatUpdate =  $this->resourceFormat($returnColumn,$create);
                return $this->handleQueryArrayResponse($reformatUpdate,'insert machine Success');
    
            } else {
    
                return $this->handleQueryErrorArrayResponse($create,'insert machine fail');
            }
      
        // } catch (\Exception $e) {
        
        //     return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store machine');
        // }
     
    }
    public function update($id,$data,$returnColumn) {

        try {
            $update  =  Machine::find($id);

            if($update) {

            $update->update($data);
            
            $refotmatData =  $this->resourceFormat($returnColumn,$update);

            return $this->handleQueryArrayResponse($refotmatData,'update machine success');

            } else {

                return $this->handleQueryErrorArrayResponse($update,'updates fail - machine id not found');
                
            }
        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update blog');
        }
    }
    public function destroy($id) {

     
        try {
            $remove =  Machine::where('id',$id)->delete();

            if($remove == true)
            {
                return $this->handleQueryArrayResponse($remove,'destroy machine success');
            }
            else
            {
                return $this->handleQueryErrorArrayResponse($remove,'destroy machine fail');
            }

        } 
        catch (\Exception $e) {
            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destory product');
        }
    } 

    private  function resourceFormat($returnCollection,$data) {

        return new MachineShowAllResource([
            'data' => $data,
            'status' => true
        ]);
    }

}