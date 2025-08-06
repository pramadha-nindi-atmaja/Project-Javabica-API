<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\BlogCategoryRequest\PublicCategoryBlogGetRequest;

use App\Interfaces\BlogCategoryInterface;
use Illuminate\Http\Request;

class ShowPublicCategoryBlogController extends BaseController
{
    private $blogCategoryInterface;

    public function __construct(BlogCategoryInterface $blogCategoryInterface)
    {
        $this->blogCategoryInterface = $blogCategoryInterface;
    }

    public function show(PublicCategoryBlogGetRequest $request) // Mengganti penggunaan request
    {
        $selectedColumn = array('*');

        $getBlogCategory = $this->blogCategoryInterface->show($request, $selectedColumn);
 
        if ($getBlogCategory['queryStatus']) {
            return $this->handleResponse($getBlogCategory['queryResponse'], 'get CategoryBlog success', $request->all(), str_replace('/', '.', $request->path()), 201);
        }

        $data = array([
            'field'   => 'show-category-blog',
            'message' => 'error when showing category blog'
        ]);

        return $this->handleError($data, $getBlogCategory['queryMessage'], $request->all(), str_replace('/', '.', $request->path()), 422);
    }
}
