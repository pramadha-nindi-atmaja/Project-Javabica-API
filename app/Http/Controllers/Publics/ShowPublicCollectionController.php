<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryAndCollectionPublicRequest\CategoryPublicGetRequest;
use App\Http\Requests\CategoryAndCollectionPublicRequest\CollectionPublicGetRequest;
use App\Interfaces\TaxonomyInterface;

class ShowPublicCollectionController extends BaseController
{
    private $taxonomyInterface;

    public function __construct(TaxonomyInterface $taxonomyInterface)
    {
        $this->taxonomyInterface            = $taxonomyInterface;
     
    }
     /**
     * @lrd:start
     * # keyword untuk pencarian akan mencari :  taxonomy name dan atau taxonomy slug dan atau taxonomy parent dan atau taxonomy type
     *
     *
     * @lrd:end
     */
    public function show(CollectionPublicGetRequest $request) {

           
       
            $selectedColumn = array('*');

             $getTaxonomy = $this->taxonomyInterface->show($request,$selectedColumn);
        
        if($getTaxonomy['queryStatus']) {
            
            return $this->handleResponse( $getTaxonomy['queryResponse'],'get collection success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-user',
            'message'=> 'error when show collection'
        ]);

        return   $this->handleError( $data,$getTaxonomy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);

    }
}
