<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@php echo $invoice->name @endphp</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style type="text/css" media="screen">
            html {
                font-family: sans-serif;
                line-height: 1.15;
                margin: 0;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: #fff;
                font-size: 10px;
                margin: 36pt;
            }

            h4 {
                margin-top: 0;
                margin-bottom: 0.5rem;
            }

            p {
                margin-top: 0;
                margin-bottom: 0.3rem;
            }

            strong {
                font-weight: bolder;
            }

            img {
                vertical-align: middle;
                border-style: none;
            }

            table {
                border-collapse: collapse;
            }

            th {
                text-align: inherit;
            }

            h4, .h4 {
                margin-bottom: 0.5rem;
                font-weight: 500;
                line-height: 1.2;
            }

            h4, .h4 {
                font-size: 1.5rem;
            }

            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
            }

            .table th,
            .table td {
                padding: 0.75rem;
                vertical-align: top;
            }

            .table.table-items td {
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .mt-5 {
                margin-top: 3rem !important;
            }
            .mt-2 {
                margin-top: 1rem !important;
            }

            .pr-0,
            .px-0 {
                padding-right: 0 !important;
            }

            .pl-0,
            .px-0 {
                padding-left: 0 !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-center {
                text-align: center !important;
            }

            .text-uppercase {
                text-transform: uppercase !important;
            }
            /** {
                font-family: "DejaVu Sans";
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                line-height: 1.1;
            }
            .party-header {
                font-size: 0.8rem;
                font-weight: 700;
            }
            .total-amount {
                font-size: 12px;
                font-weight: 700;
            }
            .border-0 {
                border: none !important;
            }
            .border-1 {
                border: 1px solid #000 !important;
            }
            .border-top-bottom {
                border-top:1px solid #000;
                  border-bottom:1px solid #000;
            }
            .cool-gray {
                color: #6B7280;
            } */
        </style>
    </head>

    <body>
        @php
            $voucherDiscount = 0;
            if($invoice->voucher){
                if ($invoice->voucher->type == '1'){
                    $voucherDiscount = $invoice->voucher->amount;
                }
                else {
                    $voucherDiscount = ($invoice->total_amount - $invoice->shipping_amount) - (($invoice->total_amount - $invoice->shipping_amount) * ($invoice->voucher->amount / 100));
                }
            }
        @endphp
        {{-- Header --}}
        <?php if($invoice->logo): ?>
            <table class="table mt-3">
                <tbody class="border-0">
                    <tr>
                        <td  class="border-0 pl-0" width="20%">
                            <img src="@php echo $invoice->getLogo() @endphp" alt="logo" class="p-0" height="40">
                        </td>
                        <td class="border-0">
                            <p style="font-weight:700;margin-bottom:0px">@php echo $invoice->companyDetail->coporate_name @endphp</p>
                            <p style="margin-bottom:1px">@php echo $invoice->companyDetail->address @endphp</p>
                            <p style="margin-bottom:0px">Telp:@php echo $invoice->companyDetail->phone @endphp | email:@php echo $invoice->companyDetail->email @endphp | website:@php echo $invoice->companyDetail->website @endphp </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        <table class="table mt-3">
            <tbody>
                <tr>
                    <td class="border-0 pl-0" width="70%">
                        <h4 class="text-uppercase text-center">
                            <strong>@php echo $invoice->name @endphp</strong> 
                            
                        </h4>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Seller - Buyer --}}
        <table class="table">
            <thead>
                <tr>
                    <th class="border-top-bottom pl-0 party-header" >
                        @php echo __('invoices::invoice.buyer') @endphp
                    </th>
                    <th class="border-0" width="1%"></th>
                    <th class="border-top-bottom pl-0 party-header" width="48.5%">     
                        @php echo __('invoices::invoice.invoice_info') @endphp
                    </th>
                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
                        <?php if($invoice->buyer->name): ?>
                            <p class="buyer-name">
                                @php echo __('invoices::invoice.buyer_name') @endphp  <strong>@php echo $invoice->buyer->name @endphp</strong>
                            
                            </p>
                            
                        <?php endif; ?>
                        <?php foreach($invoice->buyer->custom_fields as $key => $value): ?>
                            <p class="buyer-custom-field">
                                @php echo ucfirst($key) @endphp: @php echo $value @endphp
                            </p>
                        <?php endforeach; ?>
                    </td>
                    <td class="px-0"></td>
                    <td class="px-0">
                        <table class="table px-0" width="100%">
                            <thead>
                                <tr>
                                    <td class="border-1 px-1" width="50%">
                                        Date (dd/mm/yyyy)<br/>
                                        <span style="font-weight:700">
                                            <?php echo $invoice->getDate(); ?>
                                        </span>
                                    </td>
                                    <td class="border-1" >Invoice No
                                        <span style="font-weight:700">
                                            <?php echo $invoice->getSerialNumber(); ?>
                                        </span>  
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-1">Syarat Pembayaran
                                        <span style="font-weight:700">
                                            <br/>-
                                        </span>
                                    </td>
                                    <td class="border-1" >No PO
                                        <span style="font-weight:700">
                                            <br />-
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-1">
                                        <span>
                                            <?php echo DNS2D::getBarcodeHTML(''.$invoice->getSerialNumber().'', 'QRCODE',2,2); ?>
                                        </span>
                                    </td>
                                    <td class="border-1">
                                        Payment Status
                                        <br/><br/>
                                        <?php if($invoice->status == 'SUCCESS'): ?>
                                            <span style="color:green;font-weight:700">
                                                <?php echo $invoice->status; ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="font-weight:700">
                                                <?php echo $invoice->status; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </td>                        
                </tr>
            </tbody>
        </table>

        {{-- Table --}}
        <table class="table table-items">
            <thead>
                <tr>
                    <th scope="col" class="border-0 pl-0">Kode</th>
                    <th scope="col" class="border-0 pl-0"><?php echo __('invoices::invoice.description'); ?></th>
                    <?php if($invoice->hasItemUnits): ?>
                        <th scope="col" class="text-center border-0"><?php echo __('invoices::invoice.units'); ?></th>
                    <?php endif; ?>
                    <th scope="col" class="text-center border-0"><?php echo __('invoices::invoice.quantity'); ?></th>
                    <th scope="col" class="text-right border-0"><?php echo __('invoices::invoice.price'); ?></th>
                    <?php if($invoice->hasItemDiscount): ?>
                        <th scope="col" class="text-right border-0"><?php echo __('invoices::invoice.discount'); ?></th>
                    <?php endif; ?>
                    <?php if($invoice->hasItemTax): ?>
                        <th scope="col" class="text-right border-0"><?php echo __('invoices::invoice.tax'); ?></th>
                    <?php endif; ?>
                    <th scope="col" class="text-right border-0 pr-0"><?php echo __('invoices::invoice.sub_total'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($invoice->items as $item): ?>
                <tr>
                    <td width="20%">
                        <?php echo $item->product_code; ?>
                    </td>
                    <td class="pl-2" width="30%">
                        <?php echo $item->title; ?>
                        <?php if($item->description): ?>
                            <p class="cool-gray"><?php echo $item->description; ?></p>
                        <?php endif; ?>
                        <?php if($item->note): ?>
                            <p class="mt-2">catatan:</p>
                            <p class="cool-gray"><?php echo $item->note; ?></p>
                        <?php endif; ?>
                    </td>
                    <?php if($invoice->hasItemUnits): ?>
                        <td class="text-center"><?php echo $item->units; ?></td>
                    <?php endif; ?>
                    <td class="text-center"><?php echo $item->quantity; ?></td>
                    <td class="text-right">
                        <?php echo $invoice->formatCurrency($item->price_per_unit); ?>
                    </td>
                    <?php if($invoice->hasItemDiscount): ?>
                        <td class="text-right">
                            <?php echo $invoice->formatCurrency($item->discount); ?>
                        </td>
                    <?php endif; ?>
                    <?php if($invoice->hasItemTax): ?>
                        <td class="text-right">
                            <?php echo $invoice->formatCurrency($item->tax); ?>
                        </td>
                    <?php endif; ?>
        
                    <td class="text-right pr-0">
                        <?php echo $invoice->formatCurrency($item->sub_total_price); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td style="border-bottom:1px solid #000;padding-bottom:1rem;padding-top:0.1rem" colspan="6"></td>
                </tr>
               
                <tr>
                    <td style="padding-top:0.2rem" colspan="6"></td>
                </tr>
                <tr >
                    <td class="border-0">
                        Terbilang :
                    </td>
                    <td colspan="5" style="border:1px solid #000;" class="px-2 ">
                        <?php if($invoice->voucher): ?>
                            <?php if($invoice->voucher->type == '1'): ?>
                                <?php echo Terbilang::make($invoice->total_amount - $voucherDiscount, ' rupiah', 'senilai '); ?>
                            <?php else: ?>
                                <?php echo Terbilang::make($invoice->total_amount - (($invoice->total_amount - $invoice->shipping_amount) - $voucherDiscount), ' rupiah', 'senilai '); ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo Terbilang::make($invoice->total_amount, ' rupiah', 'senilai '); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if($invoice->hasItemOrInvoiceDiscount()): ?>
                    <tr>
                        <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0"></td>
                        <td class="text-right pl-0"><?php echo e(__('invoices::invoice.total_discount')); ?></td>
                        <td class="text-right pr-0">
                            <?php echo $invoice->formatCurrency($invoice->total_discount); ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if($invoice->taxable_amount): ?>
                    <tr>
                        <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0"></td>
                        <td class="text-right pl-0"><?php echo e(__('invoices::invoice.taxable_amount')); ?></td>
                        <td class="text-right pr-0">
                            <?php echo $invoice->formatCurrency($invoice->taxable_amount); ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if($invoice->tax_rate): ?>
                    <tr>
                        <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0"></td>
                        <td class="text-right pl-0"><?php echo e(__('invoices::invoice.tax_rate')); ?></td>
                        <td class="text-right pr-0">
                            <?php echo e($invoice->tax_rate); ?>%
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if($invoice->hasItemOrInvoiceTax()): ?>
                    <tr>
                        <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0"></td>
                        <td class="text-right pl-0"><?php echo e(__('invoices::invoice.total_taxes')); ?></td>
                        <td class="text-right pr-0">
                            <?php echo $invoice->formatCurrency($invoice->total_taxes); ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if($invoice->shipping_amount): ?>
                    <tr>
                        <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0"></td>
                        <td class="text-right pl-0" width="20%"><?php echo e(__('invoices::invoice.shipping')); ?></td>
                        <td class="text-right pr-0">
                            <?php echo $invoice->formatCurrency($invoice->shipping_amount); ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php if($invoice->voucher): ?>
                    <?php if($invoice->voucher->type == '1'): ?>
                        <tr>
                            <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0"></td>
                            <td class="text-right pl-0" width="20%"><?php echo e(__('Voucher')); ?></td>
                            <td class="text-right pr-0">
                                - <?php echo $invoice->formatCurrency($voucherDiscount); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0">
                            
                            </span>
                            </td>
                            <td class="text-right pl-0" style="font-weight:700"><?php echo e(__('invoices::invoice.total_amount')); ?></td>
                            <td class="text-right pr-0 total-amount">
                                <?php echo $invoice->formatCurrency($invoice->total_amount - $voucherDiscount); ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0"></td>
                            <td class="text-right pl-0" width="20%"><?php echo e(__('Voucher')); ?></td>
                            <td class="text-right pr-0">
                                - <?php echo $invoice->formatCurrency(($invoice->total_amount - $invoice->shipping_amount) - $voucherDiscount); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0">
                            
                            </span>
                            </td>
                            <td class="text-right pl-0" style="font-weight:700"><?php echo e(__('invoices::invoice.total_amount')); ?></td>
                            <td class="text-right pr-0 total-amount">
                                <?php echo $invoice->formatCurrency($invoice->total_amount - (($invoice->total_amount - $invoice->shipping_amount) - $voucherDiscount)); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo e($invoice->table_columns - 1); ?>" class="border-0">
                        
                        </span>
                        </td>
                        <td class="text-right pl-0" style="font-weight:700"><?php echo e(__('invoices::invoice.total_amount')); ?></td>
                        <td class="text-right pr-0 total-amount">
                            <?php echo $invoice->formatCurrency($invoice->total_amount); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if($invoice->notes): ?>
            <table class="table" width="100%">
                <tr width="80%">
                    <td class="border-1">
                        <?php echo e(trans('invoices::invoice.notes')); ?>: <?php echo $invoice->notes; ?>
                    </td>
                    <td width="40%"></td>
                </tr>
            </table>
        <?php endif; ?>
    
        {{-- @if($invoice->notes)
            <p>
                {{ trans('invoices::invoice.notes') }}: {!! $invoice->notes !!}
            </p>
        @endif --}}

        {{-- <p>
            {{ trans('invoices::invoice.amount_in_words') }}: {{ $invoice->getTotalAmountInWords() }}
        </p>
        <p>
            {{ trans('invoices::invoice.pay_until') }}: {{ $invoice->getPayUntilDate() }}
        </p> --}}

        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
