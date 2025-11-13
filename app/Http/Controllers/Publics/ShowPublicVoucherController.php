<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\BaseController;
use App\Http\Requests\VoucherRequest\PublicVoucherGetRequest;
use App\Interfaces\VoucherInterface;

class ShowPublicVoucherController extends BaseController
{
    protected VoucherInterface $voucherInterface;

    public function __construct(VoucherInterface $voucherInterface)
    {
        $this->voucherInterface = $voucherInterface;
    }

    /**
     * Display or check public vouchers.
     */
    public function show(PublicVoucherGetRequest $request)
    {
        $selectedColumns = ['*'];

        $result = $this->voucherInterface->check_voucher($request, $selectedColumns);

        if ($result['queryStatus']) {
            return $this->handleResponse(
                $result['queryResponse'],
                $result['queryMessage'] ?? 'Get voucher success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $errorData = [
            [
                'field' => 'show-voucher',
                'message' => 'Error while fetching voucher data',
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
