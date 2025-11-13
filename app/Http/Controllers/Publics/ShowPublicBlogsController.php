<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\BlogRequest\PublicBlogGetRequest;
use App\Interfaces\BlogInterface;

class ShowPublicBlogsController extends BaseController
{
    protected BlogInterface $blogInterface;

    public function __construct(BlogInterface $blogInterface)
    {
        $this->blogInterface = $blogInterface;
    }

    /**
     * Display a list or single public blog.
     */
    public function show(PublicBlogGetRequest $request)
    {
        $selectedColumns = ['*'];

        $result = $this->blogInterface->show($request, $selectedColumns);

        if ($result['queryStatus']) {
            return $this->handleResponse(
                $result['queryResponse'],
                'Get blog success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            [
                'field' => 'show-blog',
                'message' => 'Error while fetching blog data',
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
