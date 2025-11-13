<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\ProductRequest\PublicProductGetRequest;
use App\Interfaces\ProductInterface;

class ShowPublicProductController extends BaseController
{
    protected ProductInterface $productInterface;

    public function __construct(ProductInterface $productInterface)
    {
        $this->productInterface = $productInterface;
    }

    /**
     * Display public products or product detail.
     */
    public function show(PublicProductGetRequest $request)
    {
        $collectionOutput = $request->boolean('is_detail')
            ? 'show_product_detail'
            : 'show_product_thumbnail';

        $selectedColumns = ['*'];

        $result = $this->productInterface->show($request, $selectedColumns, $collectionOutput);

        if ($result['queryStatus']) {
            return $this->handleResponse(
                $result['queryResponse'],
                'Get product success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            [
                'field' => 'show-product',
                'message' => 'Error while fetching product data',
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
