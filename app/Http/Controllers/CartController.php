<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest\CreateCartRequest;
use App\Services\Cart\CheckingCartPerItemWithSummaryGroupingService;

class CartController extends BaseController
{
    /**
     * @lrd:start
     * # Example request format for upsert
     * Replace single quotes with double quotes.
     * =============
     * {
     *    "data": [
     *      {
     *        "variant_id": 1,
     *        "qty": 12,
     *        "note": "note"
     *      },
     *      {
     *        "variant_id": 1,
     *        "qty": 10,
     *        "note": "note"
     *      }
     *    ]
     * }
     * =============
     * End of upsert format example.
     * @lrd:end
     */
    public function create(
        CreateCartRequest $request,
        CheckingCartPerItemWithSummaryGroupingService $checkingCartPerItemWithSummaryGroupingService
    ) {
        $validatedData = $request->validated();

        // Process and group cart items
        $checkingData = $checkingCartPerItemWithSummaryGroupingService->groupingPerItem($validatedData['data']);

        // Handle successful grouping
        if ($checkingData['arrayStatus'] === true) {
            return $this->handleResponse(
                $checkingData['arrayResponse'],
                'Cart processed successfully',
                $validatedData,
                str_replace('/', '.', $request->path()),
                201
            );
        }

        // Handle error case
        $errorData = [
            [
                'field' => 'create-cart',
                'message' => 'Error when processing cart data',
            ],
        ];

        return $this->handleError(
            $errorData,
            $checkingData['arrayMessage'] ?? 'Cart processing failed',
            $validatedData,
            str_replace('/', '.', $request->path()),
            422
        );
    }
}
