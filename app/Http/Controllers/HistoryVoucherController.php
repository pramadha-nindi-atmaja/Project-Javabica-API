<?php

namespace App\Http\Controllers;

// Import necessary classes and namespaces
use App\Models\HistoryVoucher;
use Illuminate\Http\Request;
use App\Http\Requests\HistoryVoucherRequest\HistoryVoucherGetRequest;
use App\Interfaces\HistoryVoucherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// Define the controller class which extends BaseController
class HistoryVoucherController extends BaseController
{
    // Declare a private variable to hold the HistoryVoucherInterface instance
    private $historyVoucherInterface;

    // Constructor method to inject the HistoryVoucherInterface dependency
    public function __construct(HistoryVoucherInterface $historyVoucherInterface)
    {
        $this->historyVoucherInterface = $historyVoucherInterface;
    }

    // Method to handle showing history vouchers
    public function show(HistoryVoucherGetRequest $request)
    {
        // Define the columns to be selected from the database
        $selectedColumn = array('*');

        // Call the show method on the historyVoucherInterface with the provided request data, selected columns, and 'show_all' option
        $get = $this->historyVoucherInterface->show($request->all(), $selectedColumn, 'show_all');

        // Check if the query was successful
        if ($get['queryStatus']) {
            // Return a successful response with the retrieved data
            return $this->handleResponse($get['queryResponse'], 'get history voucher success', $request->all(), str_replace('/', '.', $request->path()), 201);
        }

        // Create an array containing the error message
        $data = array([
            'field' => 'show-history-voucher',
            'message' => 'error when show history voucher'
        ]);

        // Return an error response with the error message
        return $this->handleError($data, $get['queryMessage'], $request->all(), str_replace('/', '.', $request->path()), 422);
    }
}
