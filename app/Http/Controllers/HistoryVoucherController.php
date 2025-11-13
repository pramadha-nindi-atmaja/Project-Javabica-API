<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryVoucherRequest\HistoryVoucherGetRequest;
use App\Interfaces\HistoryVoucherInterface;
use Illuminate\Support\Str;

class HistoryVoucherController extends BaseController
{
    private HistoryVoucherInterface $historyVoucherInterface;

    public function __construct(HistoryVoucherInterface $historyVoucherInterface)
    {
        $this->historyVoucherInterface = $historyVoucherInterface;
    }

    /**
     * Display a list of history vouchers.
     *
     * @param  HistoryVoucherGetRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(HistoryVoucherGetRequest $request)
    {
        $selectedColumns = ['*'];

        $historyVouchers = $this->historyVoucherInterface->show(
            $request->validated(),
            $selectedColumns,
            'show_all'
        );

        if ($historyVouchers['queryStatus']) {
            return $this->handleResponse(
                $historyVouchers['queryResponse'],
                'History voucher retrieved successfully',
                $request->all(),
                Str::replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            'field' => 'show-history-voucher',
            'message' => 'Failed to retrieve history voucher data',
        ];

        return $this->handleError(
            $errorData,
            $historyVouchers['queryMessage'],
            $request->all(),
            Str::replace('/', '.', $request->path()),
            422
        );
    }
}
