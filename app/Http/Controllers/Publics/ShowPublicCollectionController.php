<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CategoryAndCollectionPublicRequest\CollectionPublicGetRequest;
use App\Interfaces\TaxonomyInterface;

class ShowPublicCollectionController extends BaseController
{
    protected TaxonomyInterface $taxonomyInterface;

    public function __construct(TaxonomyInterface $taxonomyInterface)
    {
        $this->taxonomyInterface = $taxonomyInterface;
    }

    /**
     * Display public collections.
     *
     * @lrd:start
     * # Search keywords will match: taxonomy name, taxonomy slug, taxonomy parent, or taxonomy type.
     * @lrd:end
     */
    public function show(CollectionPublicGetRequest $request)
    {
        $selectedColumns = ['*'];

        $result = $this->taxonomyInterface->show($request, $selectedColumns);

        if ($result['queryStatus']) {
            return $this->handleResponse(
                $result['queryResponse'],
                'Get collection success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            [
                'field' => 'show-collection',
                'message' => 'Error while fetching collection data',
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
