<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStoreRequest\{
    LocationStoreCreateRequest,
    LocationStoreDestroyRequest,
    LocationStoreShowRequest,
    LocationStoreUpdateRequest
};
use App\Interfaces\LocationStoreInterface;
use App\Models\Province;
use App\Models\Location_stores;
use App\Services\S3uploaderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LocationStoreController extends BaseController
{
    private LocationStoreInterface $locationInterface;
    private S3uploaderServices $s3uploaderService;

    public function __construct(LocationStoreInterface $locationInterface, S3uploaderServices $s3uploaderService)
    {
        $this->locationInterface = $locationInterface;
        $this->s3uploaderService = $s3uploaderService;
    }

    /**
     * Display a list of location stores with provinces.
     */
    public function show(LocationStoreShowRequest $request)
    {
        $selectedColumns = ['*'];
        $locations = $this->locationInterface->show($request->validated(), $selectedColumns, 'show_all');

        if ($locations['queryStatus']) {
            $output = [
                'list_province' => Province::all(),
                'store_list' => $locations['queryResponse'],
            ];

            return $this->handleResponse(
                $output,
                'Location stores retrieved successfully',
                $request->all(),
                Str::replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            'field' => 'show-location-store',
            'message' => 'Failed to retrieve location store data',
        ];

        return $this->handleError(
            $errorData,
            $locations['queryMessage'],
            $request->all(),
            Str::replace('/', '.', $request->path()),
            422
        );
    }

    /**
     * Create a new location store.
     */
    public function create(LocationStoreCreateRequest $request)
    {
        if ($request->hasFile('image_upload')) {
            $this->uploaderValidation($request);
            $fileData = $this->s3uploaderService->uploads3Storage($request->file('image_upload'), 'dynamic');
            $request->merge(['image' => $fileData['arrayResponse']['filePath']]);
        } else {
            $request->merge(['image' => $request->image_upload ?? null]);
        }

        $create = $this->locationInterface->store($request->validated(), 'show_all');

        if ($create['queryStatus']) {
            return $this->handleResponse(
                $create['queryResponse'],
                'Location store created successfully',
                $request->all(),
                Str::replace('/', '.', $request->path()),
                201
            );
        }

        $errorData = [
            'field' => 'create-location-store',
            'message' => 'Failed to create location store',
        ];

        return $this->handleError(
            $errorData,
            $create['queryMessage'],
            $request->all(),
            Str::replace('/', '.', $request->path()),
            422
        );
    }

    /**
     * Update an existing location store.
     */
    public function update(LocationStoreUpdateRequest $request)
    {
        if ($request->hasFile('image_upload')) {
            $this->uploaderValidation($request);

            $store = Location_stores::find($request->id);
            $existingImage = $store?->image;

            $fileData = $this->s3uploaderService->uploads3Storage(
                $request->file('image_upload'),
                'dynamic',
                $existingImage
            );

            $request->merge(['image' => $fileData['arrayResponse']['filePath']]);
        } else {
            $request->merge(['image' => $request->image_upload ?? null]);
        }

        $update = $this->locationInterface->update($request->id, $request->except(['id']), 'show_all');

        if ($update['queryStatus']) {
            return $this->handleResponse(
                $update['queryResponse'],
                'Location store updated successfully',
                $request->all(),
                Str::replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            'field' => 'update-location-store',
            'message' => 'Failed to update location store',
        ];

        return $this->handleError(
            $errorData,
            $update['queryMessage'],
            $request->all(),
            Str::replace('/', '.', $request->path()),
            422
        );
    }

    /**
     * Delete a location store.
     */
    public function delete(LocationStoreDestroyRequest $request)
    {
        $destroy = $this->locationInterface->destroy($request->by_id);

        if ($destroy['queryStatus']) {
            $successData = [
                'field' => 'destroy-location-store',
                'message' => 'Location store deleted successfully',
            ];

            return $this->handleResponse(
                $successData,
                'Location store deleted successfully',
                $request->all(),
                Str::replace('/', '.', $request->path()),
                204
            );
        }

        $errorData = [
            'field' => 'destroy-location-store',
            'message' => 'Failed to delete location store',
        ];

        return $this->handleError(
            $errorData,
            $destroy['queryMessage'],
            $request->all(),
            Str::replace('/', '.', $request->path()),
            422
        );
    }

    /**
     * Retrieve all provinces.
     */
    public function province(Request $request)
    {
        $provinces = Province::all();

        return $this->handleResponse(
            $provinces,
            'Provinces retrieved successfully',
            $request->all(),
            Str::replace('/', '.', $request->path()),
            200
        );
    }

    /**
     * Validate uploaded image before sending to S3.
     */
    private function uploaderValidation(Request $request): void
    {
        $validator = Validator::make($request->only(['image_upload']), [
            'image_upload' => config('formValidation.image_upload'),
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }
    }
}
