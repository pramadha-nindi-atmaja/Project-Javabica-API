<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest\CheckoutCreateRequest;
use App\Interfaces\OrderInterface;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\HistoryVoucher;
use App\Models\Order_product;
use App\Models\User_shipping_address;
use App\Services\Cart\CheckingCartPerItemWithSummaryGroupingService;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\OrderCalculationService;
use App\Services\OrderNumberGeneratorService;
use App\Services\OrderReduceStockServices;
use App\Services\RajaOngkir\CostServices;
use App\Services\RajaOngkir\CourierFinderServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends BaseController
{
    private CheckingCartPerItemWithSummaryGroupingService $checkingCartService;

    public function __construct(CheckingCartPerItemWithSummaryGroupingService $checkingCartService)
    {
        $this->checkingCartService = $checkingCartService;
    }

    /**
     * @lrd:start
     * # Example request format for checkout
     * Replace single quotes with double quotes.
     * =============
     * {
     *   "data": {
     *     "shipping": { "address_id": 1 },
     *     "billing": { "address_id": 1, "same_as_shipping": 1 },
     *     "courier": {
     *       "agent": "jne",
     *       "service": "sam day",
     *       "price": 13000,
     *       "etd": "4-5"
     *     },
     *     "product": [
     *       { "variant_id": 1, "qty": 2, "note": "" },
     *       { "variant_id": 2, "qty": 1, "note": "" }
     *     ],
     *     "voucher": 1
     *   }
     * }
     * =============
     * End of example.
     * @lrd:end
     */
    public function create(
        CheckoutCreateRequest $request,
        CostServices $costService,
        CourierFinderServices $courierFinderService,
        OrderInterface $orderInterface,
        OrderNumberGeneratorService $orderNumberGenerator,
        CreateSnapTokenService $createSnapTokenService,
        OrderCalculationService $orderCalculationService,
        OrderReduceStockServices $orderReduceStockServices
    ) {
        $validated = $request->validated();
        $data = $validated['data'];

        $billingId  = $data['billing']['same_as_shipping'] ? $data['shipping']['address_id'] : $data['billing']['address_id'];
        $shippingId = $data['shipping']['address_id'];
        $productOrder = $data['product'];
        $courier = $data['courier'];
        $voucherId = $data['voucher'] ?? null;

        // Validate stock and cart
        $checkingData = $this->checkingCart($productOrder);
        if ($checkingData['arrayStatus'] === false) {
            return $this->handleError(
                $checkingData['arrayResponse'],
                'Invalid cart data',
                $validated,
                str_replace('/', '.', $request->path()),
                422
            );
        }

        // Check shipping address
        $shippingAddress = User_shipping_address::where('id', $shippingId)
            ->where('fk_user_id', Auth::id())
            ->first();

        if (!$shippingAddress) {
            return $this->handleError(
                [['field' => 'shipping_address', 'message' => 'Shipping address not found']],
                'Shipping address not found',
                $validated,
                str_replace('/', '.', $request->path()),
                422
            );
        }

        // Check billing address
        $billingAddress = User_shipping_address::where('id', $billingId)
            ->where('fk_user_id', Auth::id())
            ->first();

        if (!$billingAddress) {
            return $this->handleError(
                [['field' => 'billing_address', 'message' => 'Billing address not found']],
                'Billing address not found',
                $validated,
                str_replace('/', '.', $request->path()),
                422
            );
        }

        // Check courier cost via RajaOngkir
        $costPayload = [
            'origin'      => config('rajaongkir.originCity'),
            'destination' => $shippingAddress->city,
            'weight'      => $checkingData['arrayResponse']['calculation']['total_weight'],
            'courier'     => $courier['agent'],
        ];

        $costResult = $costService->getCost($costPayload);
        if ($costResult['arrayStatus'] === false) {
            $message = $costResult['arrayResponse']['response']['rajaongkir']['status']['description'] ?? 'Courier service failed';
            return $this->handleError(
                [['field' => 'rajaongkir', 'message' => $message]],
                'Courier cost check failed',
                $validated,
                str_replace('/', '.', $request->path()),
                422
            );
        }

        // Find matching courier service
        $foundCourier = $courierFinderService->courierCostFinder($costResult['arrayResponse'][0]['costs'], $courier);
        if ($foundCourier['arrayStatus'] !== true) {
            return $this->handleError(
                [['field' => 'courier', 'message' => 'Invalid courier cost input']],
                'Courier validation failed',
                $validated,
                str_replace('/', '.', $request->path()),
                422
            );
        }

        // Double-check stock before reducing
        $recheck = $this->checkingCart($productOrder);
        if ($recheck['arrayStatus'] === false) {
            return $this->handleError(
                $recheck['arrayResponse'],
                'Cart validation failed',
                $validated,
                str_replace('/', '.', $request->path()),
                422
            );
        }

        // Reduce stock
        $orderReduceStockServices->reduceStock($recheck['arrayResponse']['cart']);

        // Generate order number
        $orderNumber = $orderNumberGenerator->generate();

        // Build order payload
        $payloadOrder = [
            'queue_number'               => $orderNumber['arrayResponse']['queue_number'],
            'order_number'               => $orderNumber['arrayResponse']['invoice_number'],
            'uuid'                       => Str::uuid() . '-' . date('Ymd-His'),
            'contact_email'              => Auth::user()->email,
            'shipping_country'           => 'Indonesia',
            'contact_phone'              => $shippingAddress->phone_number,
            'shipping_first_name'        => $shippingAddress->first_name,
            'shipping_last_name'         => $shippingAddress->last_name,
            'shipping_address'           => $shippingAddress->address,
            'shipping_city'              => $shippingAddress->city_label,
            'shipping_province'          => $shippingAddress->province_label,
            'shipping_postal_code'       => $shippingAddress->postal_code,
            'shipping_label_place'       => $shippingAddress->label_place,
            'shipping_note_address'      => $shippingAddress->courier_note,
            'billing_country'            => 'Indonesia',
            'contact_billing_phone'      => $billingAddress->phone_number,
            'billing_first_name'         => $billingAddress->first_name,
            'billing_last_name'          => $billingAddress->last_name,
            'billing_address'            => $billingAddress->address,
            'billing_city'               => $billingAddress->city_label,
            'billing_province'           => $billingAddress->province_label,
            'billing_postal_code'        => $billingAddress->postal_code,
            'billing_label_place'        => $billingAddress->label_place,
            'billing_note_address'       => $billingAddress->courier_note,
            'courier_agent'              => $courier['agent'],
            'courier_agent_service'      => $foundCourier['arrayResponse'][0]['service'],
            'courier_agent_service_desc' => $foundCourier['arrayResponse'][0]['description'],
            'courier_estimate_delivered' => $foundCourier['arrayResponse'][0]['cost'][0]['etd'],
            'courier_resi_number'        => '',
            'courier_cost'               => $foundCourier['arrayResponse'][0]['cost'][0]['value'],
            'payment_method'             => 'Midtrans',
            'payment_refrence_code'      => '',
            'invoice_note'               => config('javabica.invoice_note'),
            'delivery_order_note'        => config('javabica.delivery_order_note'),
            'fk_user_id'                 => Auth::id(),
            'fk_voucher_id'              => $voucherId,
            'payment_status'             => 'UNPAID',
            'status'                     => 'ORDER',
        ];

        $insert = $orderInterface->store($payloadOrder, 'show_all');
        if ($insert['queryStatus'] !== true) {
            return $this->handleError(
                [['field' => 'order', 'message' => 'Order creation failed']],
                'Order insertion failed',
                $validated,
                str_replace('/', '.', $request->path()),
                422
            );
        }

        // Save voucher history if applied
        if ($voucherId) {
            HistoryVoucher::create([
                'voucher_id' => $voucherId,
                'user_id'    => Auth::id(),
                'order_id'   => $insert['queryResponse']['data']['id'],
            ]);
        }

        // Insert ordered products
        $cartItems = [];
        $midtransItems = [];

        foreach ($recheck['arrayResponse']['cart'] as $cart) {
            $cartItems[] = [
                'fk_product_id'      => $cart['product_id'],
                'fk_variant_id'      => $cart['variant_id'],
                'product_name'       => $cart['product_name'],
                'image'              => $cart['product_image'],
                'sku'                => $cart['variant_sku'],
                'variant_description'=> $cart['variant_description'],
                'qty'                => $cart['qty'],
                'acctual_price'      => $cart['price_info']['price'],
                'discount_price'     => $cart['price_info']['discount'],
                'purchase_price'     => $cart['purchase_price'],
                'note'               => $cart['note'],
                'fk_order_id'        => $insert['queryResponse']['data']['id'],
                'created_at'         => now(),
                'updated_at'         => now(),
            ];

            $midtransItems[] = [
                'id'       => $cart['variant_sku'],
                'price'    => $cart['purchase_price'],
                'quantity' => $cart['qty'],
                'name'     => strlen($cart['product_name']) > 42 ? substr($cart['product_name'], 0, 42) . '...' : $cart['product_name'],
            ];
        }

        Order_product::insert($cartItems);

        // Add shipping & voucher to Midtrans payload
        $shippingBill = [
            'id'       => 'shipping-' . $courier['agent'] . '-' . $foundCourier['arrayResponse'][0]['service'],
            'price'    => $foundCourier['arrayResponse'][0]['cost'][0]['value'],
            'quantity' => 1,
            'name'     => $courier['agent'] . '-' . $foundCourier['arrayResponse'][0]['service'],
        ];

        $getCalculation = $orderCalculationService->orderCalculation($insert['queryResponse']['data']['id']);
        $voucherData = Voucher::find($voucherId);

        $voucherBill = $voucherData
            ? [
                'id' => $voucherData->code,
                'price' => -abs($getCalculation['arrayResponse']['discount']),
                'quantity' => 1,
                'name' => 'Discount Voucher',
            ]
            : [
                'id' => 'no-voucher',
                'price' => 0,
                'quantity' => 1,
                'name' => 'No Discount Voucher',
            ];

        $midtransItems[] = $shippingBill;
        $midtransItems[] = $voucherBill;

        $transactionDetail = [
            'order_id'     => $orderNumber['arrayResponse']['invoice_number'],
            'gross_amount' => $getCalculation['arrayResponse']['grand_total'],
        ];

        $customerDetail = [
            'first_name' => Auth::user()->name,
            'email'      => Auth::user()->email,
            'phone'      => Auth::user()->phone,
        ];

        $midtransPayload = [
            'cart'                => $midtransItems,
            'transaction_details' => $transactionDetail,
            'customer_details'    => $customerDetail,
        ];

        // Generate Midtrans Snap Token
        $snapToken = $createSnapTokenService->getSnapToken($midtransPayload);

        Order::where('id', $insert['queryResponse']['data']['id'])
            ->update(['payment_snap_token' => $snapToken]);

        $responseData = [
            'uuid'               => $insert['queryResponse']['data']['uuid'],
            'id'                 => $insert['queryResponse']['data']['id'],
            'payment_snap_token' => $snapToken,
        ];

        return $this->handleResponse(
            $responseData,
            'Checkout success',
            $validated,
            str_replace('/', '.', $request->path()),
            201
        );
    }

    /**
     * Validate cart content and stock
     */
    private function checkingCart(array $productOrder)
    {
        $checkingData = $this->checkingCartService->groupingPerItem($productOrder);

        if (count($checkingData['arrayResponse']['out_of_stock']) >= 1) {
            return $this->handleArrayErrorResponse(
                $checkingData['arrayResponse'],
                'Item out of stock',
                'info'
            );
        }

        if (count($checkingData['arrayResponse']['cart']) <= 0) {
            $data = [['field' => 'cart_empty', 'message' => 'Cart is empty, please add some products']];
            return $this->handleArrayErrorResponse($data, 'Cart empty', 'info');
        }

        return $this->handleArrayResponse(
            $checkingData['arrayResponse'],
            'Cart check success',
            'info'
        );
    }
}
