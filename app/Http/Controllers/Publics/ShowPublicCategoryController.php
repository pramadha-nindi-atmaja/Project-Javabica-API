<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CategoryAndCollectionPublicRequest\CategoryPublicGetRequest;
use App\Interfaces\TaxonomyInterface;

class ShowPublicCategoryController extends BaseController
{
    protected TaxonomyInterface $taxonomyInterface;

    public function __construct(TaxonomyInterface $taxonomyInterface)
    {
        $this->taxonomyInterface = $taxonomyInterface;
    }

    /**
     * Display public taxonomy categories.
     */
    public function show(CategoryPublicGetRequest $request)
    {
        $selectedColumns = ['*'];

        $result = $this->taxonomyInterface->show($request, $selectedColumns);

        if ($result['queryStatus']) {
            return $this->handleResponse(
                $result['queryResponse'],
                'Get category success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            [
                'field' => 'show-category',
                'message' => 'Error while fetching taxonomy data',
            ],
        ];

        return $this->handleError(
            $errorData,
            $result['queryMessage'] ?? 'Unknown error',
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }
}
