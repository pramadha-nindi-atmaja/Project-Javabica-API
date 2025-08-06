<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $invoice->name; ?></title>
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
        * {
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
        }
    </style>
</head>

<body>
    <?php if($invoice->logo): ?> 
        <table class="table mt-3">
            <tbody class="border-0">
                <tr>
                    <td class="border-0 pl-0" width="20%">
                        <img src="<?php echo $invoice->getLogo(); ?>" alt="logo" class="p-0" height="40">
                    </td>
                    <td class="border-0">
                        <p style="font-weight:700;margin-bottom:0px"><?php echo $invoice->companyDetail->coporate_name; ?></p>
                        <p style="margin-bottom:1px"><?php echo $invoice->companyDetail->address; ?></p>
                        <p style="margin-bottom:0px">Telp:<?php echo $invoice->companyDetail->phone; ?> | email:<?php echo $invoice->companyDetail->email; ?> | website:<?php echo $invoice->companyDetail->website; ?></p>
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
                        <strong>DELIVERY ORDER</strong>
                    </h4>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th class="border-top-bottom pl-0 party-header" >
                    <?php echo __('invoices::invoice.buyer'); ?>
                </th>
                <th class="border-0" width="1%"></th>
                <th class="border-top-bottom pl-0 party-header" width="48.5%">
                    <?php echo __('invoices::invoice.invoice_info'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-0">
                    <?php if($invoice->buyer->name): ?>
                        <p class="buyer-name">
                            <?php echo __('invoices::invoice.buyer_name'); ?>  <strong><?php echo $invoice->buyer->name; ?></strong>
                        </p>
                    <?php endif; ?>
                    <?php foreach($invoice->buyer->custom_fields as $key => $value): ?>
                        <p class="buyer-custom-field">
                            <?php echo ucfirst($key); ?>: <?php echo $value; ?>
                        </p>
                    <?php endforeach; ?>
                </td>
                <td class="px-0">
                </td>
                <td class="px-0">
                    <table class="table px-0" width="100%">
                        <thead>
                            <tr>
                                <td class="border-1 px-1" width="50%">
                                    Tanggal <br/>
                                    <span style="font-weight:700">
                                        <?php echo $invoice->getDate(); ?>
                                    </span>
                                </td>
                                <td class="border-1" >Nomor Invoice
                                    <span style="font-weight:700">
                                        <?php echo $invoice->getSerialNumber(); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-1">Ekspedisi
                                    <span style="font-weight:700">
                                    <br/><?php echo $invoice->ekspedisi; ?>
                                    </span>
                                </td>
                                <td class="border-1" >PO No
                                    <span style="font-weight:700">
                                    <br />-
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-1" colspan="2">
                                    <span>
                                        <?php echo DNS2D::getBarcodeHTML(''.$invoice->getSerialNumber().'', 'QRCODE',2,2); ?>
                                    </span>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-items">
        <thead>
            <tr>
                <th scope="col" class="border-0 pl-0">Kode</th>
                <th scope="col" class="border-0 pl-0"><?php echo __('invoices::invoice.description'); ?></th>
                <?php if($invoice->hasItemUnits): ?>
                    <th scope="col" class="text-center border-0"><?php echo __('invoices::invoice.units'); ?></th>
                <?php endif; ?>
                <th scope="col" class="text-center border-0"><?php echo __('invoices::invoice.quantity'); ?></th>
                <th scope="col" class="text-center border-0">Satuan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($invoice->items as $item): ?>
                <tr>
                    <td width="30%">
                        <?php echo $item->product_code; ?>
                    </td>
                    <td class="pl-2"width="100%">
                        <?php echo $item->title; ?>
                        <?php if($item->description): ?>
                            <p class="cool-gray"><?php echo $item->description; ?></p>
                        <?php endif; ?>
                        <?php if($item->note): ?>
                            <p class="mt-2">catatan:</p>
                            <p class="cool-gray"><?php echo $item->note; ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">Item</td>
                    <td class="text-center"width="30%" ><?php echo $item->quantity; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="<?php echo $invoice->table_columns - 3; ?>" ></td>
                <td class="text-center pl-0" width="40%">Total Kuantitas</td>
                <td class="text-center pr-0">
                    <?php echo $invoice->total_item; ?>
                </td>
            </tr>
            <tr>
                <td colspan="<?php echo $invoice->table_columns - 3; ?>" class="border-0"></td>
                <td class="text-center pl-0">Total Item</td>
                <td class="text-center pr-0">
                    <?php echo $invoice->total_product; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php if($invoice->notes): ?>
        <table class="table " width="100%">
            <tr  width="80%">
                <td  class="border-1">
                    <?php echo trans('invoices::invoice.notes'); ?>: <?php echo $invoice->notes; ?>
                </td>
                <td width="40%" ></td>
            </tr>
        </table>
    <?php endif; ?>
    <table class="table mt-3 border-0">
        <tr>
            <td width="33%" class="border-0">
                <table width="100%">
                    <tr>
                        <td class="text-center" width="width:80%">Pengirim</td>
                    </tr>
                    <tr class="border-0" width="80%">
                        <td class="text-center" width="80%" >
                            <span >
                            <br/>
                            <br/>
                            <br/>
                            <br/>
                            </span>
                            <hr />
                        </td>
                    </tr>
                </table>
            </td>
            <td width="33%" class="border-0">
                <table width="100%">
                    <tr>
                        <td class="text-center" width="width:80%">Gudang</td>
                    </tr>
                    <tr class="border-0" width="80%">
                        <td class="text-center" width="80%" >
                            <span >
                            <br/>
                            <br/>
                            <br/>
                            <br/>
                            </span>
                            <hr />
                        </td>
                    </tr>
                </table>
            </td>
            <td width="33%" class="border-0">
                <table width="100%">
                    <tr>
                        <td class="text-center" width="width:80%">Penerima</td>
                    </tr>
                    <tr class="border-0" width="80%">
                        <td class="text-center" width="80%" >
                            <span >
                            <br/>
                            <br/>
                            <br/>
                            <br/>
                            </span>
                        <hr />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
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
