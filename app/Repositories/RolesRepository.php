<?php

namespace App\Repositories;

use App\Http\Controllers\BaseController;

use App\Http\Resources\RolePermissionResource\RoleHasPermissionResource;
use App\Interfaces\RolesInterface;
use App\Models\Model_has_permission;
use App\Models\Role_has_permission;
use Illuminate\Contracts\Pagination\Paginator;
use Spatie\Permission\Models\Role;

class RolesRepository extends BaseController implements RolesInterface 
{
    public function showRole($request,$getOnlyColumn) {

        try {
                
                    $getData =  Role::select($getOnlyColumn);

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
                                    ->map(function($item) { 
                                
                                            return $item;


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

                                    $message =   'get roles with paginate success';
                                }
                                else {
                                
                                    $outputData =  $itemsTransformed;
                                    if(count($getCollection) > 1)
                                    {
                                        $message =   'get roles data success without pagination max 250 data';
                                    }
                                    else
                                    {
                                        $message =   'get roles data success';
                                    }
                                    
                                }
                                

            return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get role');
        }
    }

    public function showPermissionFromRoles($roles_id) {

        try {
                
            $getData = Role_has_permission::select('*')
                                        ->where('role_id',$roles_id);
                                        if(request()->get('keywords') )
                                        {
                                           $keyword = request()->get('keywords');
                                            $outputData            =   $getData->whereHas('belogsTo_Permission', function ($query) use ($keyword) {
                                                $query->where('name', 'like', '%'. $keyword .'%');
                                              });
                                           
                                        }
                                        if(request()->get('paginate') == true)
                                        {
                                           
                                            $outputData            =  $getData->paginate(request()->get('per_page') ,'*','page',request()->get('currentPage'));
                                            $getCollection         =  $outputData->getCollection();
                                        }
                                        else
                                        {   
                                            $getCollection  =   $getData->limit(250)->get();
                                        }
                                        $itemsTransformed = $getCollection
                                        ->map(function($item) { 
                                         
                                                return new RoleHasPermissionResource([
                                                    'data' => $item,
                                                    'status' => true
                                                ]);
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

                                        $message =   'get roles has permission with paginate success';
                                    }
                                    else {
                                    
                                        $outputData =  $itemsTransformed;
                                        if(count($getCollection) > 1)
                                        {
                                            $message =   'get roles has permission data success without pagination max 250 data';
                                        }
                                        else
                                        {
                                            $message =   'get roles has permission data success';
                                        }
                                        
                                    }
                                    

                return $this->handleQueryArrayResponse($outputData,$message);

        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when show permission from role');
        }
    }

    public function store($data) {

     

    }
    public function update($id,$data) {
        
      

    }
    public function destroy($id) {

       
    }
   
}