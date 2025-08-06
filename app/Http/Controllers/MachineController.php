<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\MachineInterface;
use App\Http\Requests\MachineRequest\GetMachineRequestValidation;
use App\Http\Requests\MachineRequest\PublicMachineGetRequest;
use App\Http\Requests\MachineRequest\CreateMachineRequest;
use App\Http\Requests\MachineRequest\UpdateMachineRequest;
use App\Http\Requests\MachineRequest\DestroyMachineRequest;

class MachineController extends BaseController
{
    private $machineInterface;
    
    public function __construct(MachineInterface $machineInterface)
    {
        $this->machineInterface            = $machineInterface;
    }

    public function show(GetMachineRequestValidation $request) {

        $selectedColumn = array('*');

        $getMachine = $this->machineInterface->show($request,$selectedColumn);
        
        if($getMachine['queryStatus']) {
            
            return $this->handleResponse( $getMachine['queryResponse'],'get Machine success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-machine',
            'message'=> 'error when show Machine'
        ]);

        return   $this->handleError( $data,$getMachine['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
    }
    
    public function show_all_machine(PublicMachineGetRequest $request) {

        $selectedColumn = array('*');

        $getMachine = $this->machineInterface->show($request,$selectedColumn);
        
        if($getMachine['queryStatus']) {
            
            return $this->handleResponse( $getMachine['queryResponse'],'get All Machine success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-machine',
            'message'=> 'error when show Machine'
        ]);

        return   $this->handleError( $data,$getMachine['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
    }

    public function create(CreateMachineRequest $request) { 

        $insert = $this->machineInterface->store($request->all(),'show_all');
    
        if($insert['queryStatus']) {

            return $this->handleResponse( $insert['queryResponse'],'Insert machine success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-machine',
                'message'=> 'machine category create fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
    }

    public function update(UpdateMachineRequest $request) { 
        
        $update = $this->machineInterface->update($request->id,$request->except(['id']),'show_all');

        if($update['queryStatus']) {

            return $this->handleResponse( $update['queryResponse'],'update machine  success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-machine',
                'message'=> 'machine create fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }

    public function delete(DestroyMachineRequest $request) { //done
        
        //remove data
        $destroy =   $this->machineInterface->destroy($request->by_id);


        if($destroy['queryStatus']) {

            return $this->handleResponse( $destroy['queryResponse'],'Delete machine  success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'destroy-machine',
                'message'=> 'machine category create fail'
            ]);

            return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

}


}
