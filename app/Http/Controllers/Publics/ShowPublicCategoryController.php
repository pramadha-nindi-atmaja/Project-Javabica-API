<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CategoryAndCollectionPublicRequest\CategoryPublicGetRequest;

use App\Interfaces\TaxonomyInterface;


class ShowPublicCategoryController extends BaseController
{

    private $taxonomyInterface;

    public function __construct(TaxonomyInterface $taxonomyInterface)
    {
        $this->taxonomyInterface            = $taxonomyInterface;
    }

     public function show(CategoryPublicGetRequest $request)
    {
        $selectedColumn = array('*');

        $getTaxonomy = $this->taxonomyInterface->show($request, $selectedColumn);

        if ($getTaxonomy['queryStatus']) {

            return $this->handleResponse($getTaxonomy['queryResponse'], 'get Category success', $request->all(), str_replace('/', '.', $request->path()), 201);
        }

        $data  = array([
            'field' => 'show-user',
            'message' => 'error when show taxonomy'
        ]);

        return   $this->handleError($data, $getTaxonomy['queryMessage'], $request->all(), str_replace('/', '.', $request->path()), 422);
    }
}
