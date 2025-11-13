<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\BlogCategoryRequest\PublicCategoryBlogGetRequest;
use App\Interfaces\BlogCategoryInterface;

class ShowPublicCategoryBlogController extends BaseController
{
    protected BlogCategoryInterface $blogCategoryInterface;

    public function __construct(BlogCategoryInterface $blogCategoryInterface)
    {
        $this->blogCategoryInterface = $blogCategoryInterface;
    }

    /**
     * Display public blog categories.
     */
    public function show(PublicCategoryBlogGetRequest $request)
    {
        $selectedColumns = ['*'];

        $result = $this->blogCategoryInterface->show($request, $selectedColumns);

        if ($result['queryStatus']) {
            return $this->handleResponse(
                $result['queryResponse'],
                'Get category blog success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            [
                'field' => 'show-category-blog',
                'message' => 'Error while fetching category blog data',
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
