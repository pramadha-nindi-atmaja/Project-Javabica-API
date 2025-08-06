<?php

namespace App\Repositories;

use App\Interfaces\UsersInterface;
use App\Models\User;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Hash;
use App\PipelineFilters\UsersPipeline\UseSort;
use App\PipelineFilters\UsersPipeline\GetByKey;
use App\PipelineFilters\UsersPipeline\GetByWord;
use App\Http\Resources\UsersResources\UserShowAllWebResource;
use App\Http\Controllers\BaseController;
use App\Http\Resources\UsersResources\UsersShowBasicResource;

class UsersRepository extends BaseController implements UsersInterface
{

    public function show($request,$getOnlyColumn) {
        
        // $user = auth()->user();

        // $Role =  $user->getRoleNames();
        // $permissions = $user->getPermissionsViaRoles();
        // return $user;
        try {
                $getData =  app(Pipeline::class)
                                            ->send(User::query())
                                            ->through([
                                                GetByWord::class,
                                                GetByKey::class,
                                                UseSort::class,
                                            ])
                                            ->thenReturn()
                                            ->with('roles.permissions')
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
                                            ->map(function($item) {
                                                  
                                                 return $this->userResourceFormat(request()->get('collection_type'),$item);
                                        
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

                                            $message =   'show users with paginate success';
                                        }
                                        else {
                                        
                                            $outputData =  $itemsTransformed;
                                            if(count($getCollection) > 1)
                                            {
                                                $message =   'show users data success without pagination max 250 data';
                                            }
                                            else
                                            {
                                                $message =   'show users data success';
                                            }
                                            
                                        }
                              
                            return $this->handleQueryArrayResponse($outputData,$message);

            } catch (\Exception $e) {

                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when get users');
            }
    }
    public function store($data,$returnCollection='showAll') {
        
        try {
                $users                 = new User();
                $users->name           = $data['name'];
                $users->email          = $data['email'];
                $users->phone          = $data['phone'];
                $users->last_name      = $data['last_name'];
                $users->password       = Hash::make($data['password']);
                $users->dob            = $data['dob'];
                $users->phone          = $data['phone'];
                $users->gender         = $data['gender'];
                $users->save();
            

                if($users) {
                   
                    $getAfterInsertData = $this->userResourceFormat($returnCollection,$users);

                    return $this->handleQueryArrayResponse($getAfterInsertData,'insert users Success');

                } else {
                    
                    return $this->handleQueryErrorArrayResponse($users,'insert users fail');
                }
                
            } catch (\Exception $e) {

              
                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when store user');
            }
    }
    public function destroy($id) {
        try {
                $remove =  User::where('id',$id)->delete();

                if($remove)
                {
                    return $this->handleQueryArrayResponse($remove,'destroy users success');
                }
                else
                {
                    return $this->handleQueryErrorArrayResponse($remove,'destroy users fail');
                
                }

            } catch (\Exception $e) {
                return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when destroy user');
            }
    }
    public function update($id,array $data,$returnCollection='showAll') {

        try {
                $users  =  User::find($id);
            
                if($users) {

                    $users->update($data);
                    $getAfterUpdateData = $this->userResourceFormat($returnCollection,$users);

                    return $this->handleQueryArrayResponse($getAfterUpdateData,'update users success');
                    
                } else {

                    return $this->handleQueryErrorArrayResponse($users,'updates fail - users not found');
                
                }
        } catch (\Exception $e) {

            return $this->handleQueryErrorArrayResponse($e->getMessage(),'error when update user');
        }
    }
    private function userResourceFormat($formatType,$data) {

        if($formatType == 'showAll') {
            
            return  new UserShowAllWebResource([
                'data'   => $data,
                'status' => true
            ]);
        }
        else if($formatType == 'showBasic') {

            return  new UsersShowBasicResource([
                'data'   => $data,
                'status' => true
            ]);
            
        }
    }
   
}
