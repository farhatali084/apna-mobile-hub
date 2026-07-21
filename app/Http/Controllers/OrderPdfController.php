<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderPdfController extends Controller
{
    /**
     * Download Order Invoice PDF.
     */
    public function downloadPdf($order_number)
    {
        $order = Order::with(['items.product.category'])->where('order_number', $order_number)->firstOrFail();

        $pdf = Pdf::loadView('pdf.order-invoice', compact('order'));
        $pdf->setPaper('a4', 'portrait');

        $filename = "Invoice_{$order->order_number}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Stream Order Invoice PDF inline in browser.
     */
    public function viewPdf($order_number)
    {
        $order = Order::with(['items.product.category'])->where('order_number', $order_number)->firstOrFail();

        $pdf = Pdf::loadView('pdf.order-invoice', compact('order'));
        $pdf->setPaper('a4', 'portrait');

        $filename = "Invoice_{$order->order_number}.pdf";

        return $pdf->stream($filename);
    }
}
