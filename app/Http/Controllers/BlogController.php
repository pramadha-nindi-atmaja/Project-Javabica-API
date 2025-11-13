<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest\GetBlogRequestValidation;
use App\Http\Requests\BlogRequest\DestroyBlogRequest;
use App\Http\Requests\BlogRequest\CreateBlogRequest;
use App\Http\Requests\BlogRequest\UpdateBlogRequest;
use App\Interfaces\BlogInterface;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\S3uploaderServices;

class BlogController extends BaseController
{
    private BlogInterface $blogInterface;
    private S3uploaderServices $s3uploaderService;

    public function __construct(BlogInterface $blogInterface, S3uploaderServices $s3uploaderService)
    {
        $this->blogInterface = $blogInterface;
        $this->s3uploaderService = $s3uploaderService;
    }

    /**
     * Display a list of blogs or a specific blog.
     */
    public function show(GetBlogRequestValidation $request)
    {
        $selectedColumn = ['*'];
        $getBlog = $this->blogInterface->show($request, $selectedColumn);

        if ($getBlog['queryStatus']) {
            return $this->handleResponse(
                $getBlog['queryResponse'],
                'Get blog success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [
            [
                'field' => 'show-blog',
                'message' => 'Error when showing blog',
            ],
        ];

        return $this->handleError(
            $data,
            $getBlog['queryMessage'],
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    /**
     * Create a new blog entry.
     */
    public function create(CreateBlogRequest $request)
    {
        if ($request->hasFile('cover_upload')) {
            $this->validateUploader($request);
            $fileData = $this->s3uploaderService->uploads3Storage($request->file('cover_upload'), 'dynamic');

            $request->merge([
                'cover' => $fileData['arrayResponse']['filePath'],
            ]);
        } else {
            $request->merge([
                'cover' => $request->cover_upload,
            ]);
        }

        $insert = $this->blogInterface->store($request->validated(), 'show_all');

        if ($insert['queryStatus']) {
            return $this->handleResponse(
                $insert['queryResponse'],
                'Blog created successfully',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                201
            );
        }

        $data = [
            [
                'field' => 'create-blog',
                'message' => 'Blog creation failed',
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
     * Update an existing blog.
     */
    public function update(UpdateBlogRequest $request)
    {
        if ($request->hasFile('cover_upload')) {
            $this->validateUploader($request);

            $existingBlog = Blog::find($request->id);
            $existingCover = $existingBlog?->cover;

            $fileData = $this->s3uploaderService->uploads3Storage(
                $request->file('cover_upload'),
                'dynamic',
                $existingCover
            );

            $request->merge([
                'cover' => $fileData['arrayResponse']['filePath'],
            ]);
        } else {
            $request->merge([
                'cover' => $request->cover_upload,
            ]);
        }

        $update = $this->blogInterface->update(
            $request->id,
            $request->except(['id']),
            'show_all'
        );

        if ($update['queryStatus']) {
            return $this->handleResponse(
                $update['queryResponse'],
                'Blog updated successfully',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [
            [
                'field' => 'update-blog',
                'message' => 'Blog update failed',
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
     * Delete a blog entry.
     */
    public function delete(DestroyBlogRequest $request)
    {
        $destroy = $this->blogInterface->destroy($request->by_id);

        if ($destroy['queryStatus']) {
            return $this->handleResponse(
                $destroy['queryResponse'],
                'Blog deleted successfully',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [
            [
                'field' => 'destroy-blog',
                'message' => 'Blog deletion failed',
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

    /**
     * Validate the uploaded file for cover image.
     */
    private function validateUploader($request): void
    {
        $validator = Validator::make($request->only(['cover_upload']), [
            'cover_upload' => config('formValidation.image_upload'),
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }
    }
}
