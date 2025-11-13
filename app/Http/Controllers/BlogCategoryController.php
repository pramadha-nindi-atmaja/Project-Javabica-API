<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\BlogCategoryInterface;
use App\Http\Requests\BlogCategoryRequest\GetCategoryBlogRequestValidation;
use App\Http\Requests\BlogCategoryRequest\CreateCategoryBlogRequest;
use App\Http\Requests\BlogCategoryRequest\UpdateCategoryBlogRequest;
use App\Http\Requests\BlogCategoryRequest\DestroyCategoryBlogRequest;

class BlogCategoryController extends BaseController
{
    private BlogCategoryInterface $blogCategoryInterface;

    public function __construct(BlogCategoryInterface $blogCategoryInterface)
    {
        $this->blogCategoryInterface = $blogCategoryInterface;
    }

    /**
     * Display a list of blog categories.
     */
    public function show(GetCategoryBlogRequestValidation $request)
    {
        $selectedColumn = ['*'];
        $getBlogCategory = $this->blogCategoryInterface->show($request, $selectedColumn);

        if ($getBlogCategory['queryStatus']) {
            return $this->handleResponse(
                $getBlogCategory['queryResponse'],
                'Get blog category success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [
            [
                'field' => 'show-blog-category',
                'message' => 'Error when showing blog category',
            ],
        ];

        return $this->handleError(
            $data,
            $getBlogCategory['queryMessage'],
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    /**
     * Create a new blog category.
     */
    public function create(CreateCategoryBlogRequest $request)
    {
        $insert = $this->blogCategoryInterface->store($request->validated(), 'show_all');

        if ($insert['queryStatus']) {
            return $this->handleResponse(
                $insert['queryResponse'],
                'Blog category created successfully',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                201
            );
        }

        $data = [
            [
                'field' => 'create-blog-category',
                'message' => 'Blog category creation failed',
            ],
        ];

        return $this->handleError(
            $data,
            $insert['queryMessage'],
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    /**
     * Update an existing blog category.
     */
    public function update(UpdateCategoryBlogRequest $request)
    {
        $update = $this->blogCategoryInterface->update(
            $request->id,
            $request->except(['id']),
            'show_all'
        );

        if ($update['queryStatus']) {
            return $this->handleResponse(
                $update['queryResponse'],
                'Blog category updated successfully',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [
            [
                'field' => 'update-blog-category',
                'message' => 'Blog category update failed',
            ],
        ];

        return $this->handleError(
            $data,
            $update['queryMessage'],
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    /**
     * Delete a blog category.
     */
    public function delete(DestroyCategoryBlogRequest $request)
    {
        $destroy = $this->blogCategoryInterface->destroy($request->by_id);

        if ($destroy['queryStatus']) {
            return $this->handleResponse(
                $destroy['queryResponse'],
                'Blog category deleted successfully',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [
            [
                'field' => 'destroy-blog-category',
                'message' => 'Blog category deletion failed',
            ],
        ];

        return $this->handleError(
            $data,
            $destroy['queryMessage'],
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }
}
