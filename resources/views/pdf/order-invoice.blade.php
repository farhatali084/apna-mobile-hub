<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Order Invoice - #{{ $order->order_number }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #0088ff;
            padding-bottom: 12px;
        }
        .header-table td {
            vertical-align: top;
        }
        .brand-title {
            font-size: 22px;
            font-weight: 800;
            color: #0b132b;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .brand-title span {
            color: #0088ff;
        }
        .store-tagline {
            font-size: 9px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .store-details {
            text-align: right;
            font-size: 10px;
            color: #475569;
            line-height: 1.4;
        }
        .store-details strong {
            color: #0b132b;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .info-table td {
            padding: 10px 12px;
            vertical-align: top;
            width: 50%;
        }
        .info-box-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0088ff;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .info-box-content {
            font-size: 11px;
            color: #334155;
            line-height: 1.5;
        }
        .info-box-content strong {
            color: #0f172a;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #0b132b;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border: 1px solid #0b132b;
            text-align: left;
        }
        .items-table th.text-center { text-align: center; }
        .items-table th.text-right { text-align: right; }
        .items-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
            font-size: 11px;
        }
        .items-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .product-img {
            width: 45px;
            height: 45px;
            object-fit: contain;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            display: block;
            margin: 0 auto;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .totals-table td {
            vertical-align: top;
        }
        .totals-box {
            width: 260px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-box td {
            padding: 5px 8px;
            font-size: 11px;
        }
        .totals-box td.label {
            color: #64748b;
            text-align: right;
            font-weight: 600;
        }
        .totals-box td.value {
            color: #0f172a;
            text-align: right;
            font-weight: 700;
        }
        .totals-box tr.grand-total td {
            padding-top: 8px;
            border-top: 2px solid #0088ff;
            font-size: 14px;
        }
        .totals-box tr.grand-total td.label {
            color: #0b132b;
            font-weight: 800;
        }
        .totals-box tr.grand-total td.value {
            color: #0088ff;
            font-weight: 700;
        }
        .rupee-sym {
            font-family: 'DejaVu Sans', sans-serif;
            font-weight: normal !important;
            display: inline;
        }
        .footer-note {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
            font-size: 9px;
            color: #64748b;
            text-align: center;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/logo.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('apna mobile hub logo.png');
        }
        $headerLogoBase64 = file_exists($logoPath) ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath)) : null;
    @endphp

    <!-- Header Section -->
    <div style="text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #0088ff;">
        @if($headerLogoBase64)
            <img src="{{ $headerLogoBase64 }}" alt="Apna Mobile Hub Logo" style="height: 90px; width: auto; max-width: 350px; margin: 0 auto 8px auto; display: block;">
        @endif
        <div style="font-size: 24px; font-weight: 900; color: #0b132b; text-transform: uppercase; letter-spacing: -0.5px; line-height: 1.1;">
            Apna Mobile <span style="color: #0088ff;">Hub</span>
        </div>
        <div style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 4px;">
            YOUR TRUSTED B2B WHOLESALE PARTNER
        </div>
        <div style="font-size: 11px; color: #475569; margin-top: 6px; line-height: 1.4;">
            Shop No. 456, Sanjay Market, Sakchi, Jamshedpur, Jharkhand - 831001<br>
            <strong>Phone:</strong> +91 79797 47352 | <strong>Email:</strong> Apnamobilehubjsr@gmail.com
        </div>
    </div>

    <!-- Info Section: Order Summary & Customer Details -->
    <table class="info-table">
        <tr>
            <td>
                <div class="info-box-title">Order Information</div>
                <div class="info-box-content">
                    <strong>Order Number:</strong> #{{ $order->order_number }}<br>
                    <strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}<br>
                    <strong>Status:</strong> <span style="text-transform: uppercase; color: #0088ff; font-weight: 800;">{{ $order->status }}</span>
                </div>
            </td>
            <td>
                <div class="info-box-title">Customer / Buyer Details</div>
                <div class="info-box-content">
                    <strong>Name:</strong> {{ $order->customer_name }}<br>
                    <strong>Phone:</strong> {{ $order->customer_phone }}<br>
                    <strong>Address:</strong> {{ $order->customer_address }}
                    @if($order->notes)
                        <br><strong>Notes:</strong> {{ $order->notes }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- 6 Columns Product Details Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">S.No</th>
                <th class="text-center" style="width: 12%;">Image</th>
                <th style="width: 43%;">Product Name</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 15%;">Price (1 Pc)</th>
                <th class="text-right" style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                @php
                    $product = $item->product;
                    $imgSrc = null;
                    if ($product) {
                        $rawUrl = $product->getImageUrl();
                        // Convert relative or public storage image to local absolute path for DomPDF
                        if (\Illuminate\Support\Str::contains($rawUrl, 'storage/')) {
                            $relativePath = \Illuminate\Support\Str::after($rawUrl, 'storage/');
                            $localPath = storage_path('app/public/' . $relativePath);
                            if (file_exists($localPath)) {
                                $imgSrc = 'data:image/' . pathinfo($localPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($localPath));
                            }
                        } elseif (file_exists(public_path($rawUrl))) {
                            $imgSrc = 'data:image/' . pathinfo(public_path($rawUrl), PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents(public_path($rawUrl)));
                        } else {
                            $imgSrc = $rawUrl;
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center" style="font-weight: bold; color: #64748b;">{{ $index + 1 }}</td>
                    <td class="text-center">
                        @if($imgSrc)
                            <img src="{{ $imgSrc }}" class="product-img" alt="{{ $item->product_name }}">
                        @else
                            <span style="color: #94a3b8; font-size: 9px;">N/A</span>
                        @endif
                    </td>
                    <td>
                        <strong style="color: #0f172a; font-size: 11px;">{{ $item->product_name }}</strong>
                        @if($product && $product->category)
                            <br><span style="color: #64748b; font-size: 9px;">Category: {{ $product->category->name }}</span>
                        @endif
                    </td>
                    <td class="text-center" style="font-weight: bold; color: #0b132b;">{{ $item->quantity }}</td>
                    <td class="text-right"><span class="rupee-sym">₹</span> {{ number_format($item->price, 2) }}</td>
                    <td class="text-right" style="font-weight: bold; color: #0f172a;"><span class="rupee-sym">₹</span> {{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Summary Section -->
    <table class="totals-table">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 10px; color: #64748b; line-height: 1.5;">
                    * All rates are wholesale B2B pricing.<br>
                    * Minimum order value threshold applied.<br>
                    * For queries or updates, call: +91 79797 47352
                </div>
            </td>
            <td style="width: 50%;">
                <table class="totals-box">
                    <tr>
                        <td class="label">Subtotal:</td>
                        <td class="value"><span class="rupee-sym">₹</span> {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Shipping Charge:</td>
                        <td class="value">
                            @if($order->shipping_fee > 0)
                                <span class="rupee-sym">₹</span> {{ number_format($order->shipping_fee, 2) }}
                            @else
                                <span style="color: #16a34a; font-weight: 800;">FREE</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="grand-total">
                        <td class="label">Grand Total:</td>
                        <td class="value"><span class="rupee-sym">₹</span> {{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer Note -->
    <div class="footer-note">
        Thank you for doing business with <strong>Apna Mobile Hub</strong>!<br>
        Shop No. 456, Sanjay Market, Sakchi, Jamshedpur, Jharkhand - 831001
    </div>

</body>
</html>
