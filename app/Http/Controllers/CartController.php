<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Auto-heal any broken cart entries that might have missing keys
        $healed = false;
        if (is_array($cart)) {
            foreach ($cart as $id => $details) {
                if (!is_array($details) || !isset($details['price']) || !isset($details['name']) || !isset($details['image_path'])) {
                    $product = Product::find($id);
                    if ($product) {
                        $cart[$id] = [
                            "name" => $product->name,
                            "quantity" => is_array($details) ? ($details['quantity'] ?? 1) : 1,
                            "price" => $product->price,
                            "image_path" => $product->getImageUrl(),
                            "slug" => $product->slug
                        ];
                        $healed = true;
                    } else {
                        unset($cart[$id]);
                        $healed = true;
                    }
                }
            }
            if ($healed) {
                session()->put('cart', $cart);
            }
        } else {
            $cart = [];
            session()->put('cart', $cart);
        }

        return view('cart.index', compact('cart'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // If cart is empty, this is the first product
        if (!$cart) {
            $cart = [
                $id => [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "image_path" => $product->getImageUrl(),
                    "slug" => $product->slug
                ]
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added to cart successfully!');
        }

        // If cart not empty, check if this product exist then increment quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added to cart successfully!');
        }

        // If item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price,
            "image_path" => $product->getImageUrl(),
            "slug" => $product->slug
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Add multiple size variants as separate cart line items.
     * Each variant gets its own unique cart key: {productId}_{valueId}
     */
    public function addVariants(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variants'   => 'required|array|min:1',
        ]);

        $product  = Product::findOrFail($request->product_id);
        $variants = $request->variants; // [{value_id, value_name, qty, price}]
        $cart     = session()->get('cart', []);
        $added    = 0;

        foreach ($variants as $v) {
            $qty = (int) ($v['qty'] ?? 0);
            if ($qty <= 0) continue;

            // Unique cart key per product+variant so sizes show separately
            $cartKey  = $product->id . '_' . ($v['value_id'] ?? $v['value_name']);
            $itemName = $product->name . ' [' . ($v['value_name'] ?? '') . ']';

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $qty;
            } else {
                $cart[$cartKey] = [
                    'name'       => $itemName,
                    'quantity'   => $qty,
                    'price'      => $v['price'] ?? $product->price,
                    'image_path' => $product->getImageUrl(),
                    'slug'       => $product->slug,
                ];
            }
            $added++;
        }

        session()->put('cart', $cart);

        if ($added === 0) {
            return response()->json(['success' => false, 'message' => 'No valid variants selected.'], 422);
        }

        return response()->json(['success' => true, 'message' => "{$added} size(s) added to cart!", 'redirect' => route('cart.index')]);
    }

    /**
     * Update product quantity in cart.
     */
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart', []);
            if (isset($cart[$request->id]) && is_array($cart[$request->id])) {
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);
                session()->flash('success', 'Cart updated successfully!');
            }
        }
    }

    /**
     * Remove product from cart.
     */
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully!');
        }
    }

    /**
     * Single product immediate inquiry via WhatsApp.
     */
    public function inquireSingle($id)
    {
        $product = Product::findOrFail($id);
        $phone = env('WHATSAPP_ADMIN_NUMBER', '917979747352');
        $currency = env('CURRENCY_SYMBOL', '₹');
        
        $shippingFeeSetting = (float) \App\Models\Setting::getValue('shipping_fee', 0);
        $freeShippingThreshold = (float) \App\Models\Setting::getValue('free_shipping_threshold', 0);
        $shippingCharge = ($freeShippingThreshold > 0 && $product->price >= $freeShippingThreshold) ? 0 : $shippingFeeSetting;
        $total = $product->price + $shippingCharge;

        // Transactional Lead/Order Creation
        $order = null;
        try {
            DB::transaction(function () use ($product, $shippingCharge, $total, &$order) {
                $order = Order::create([
                    'customer_name' => 'Single Product Lead',
                    'customer_phone' => 'N/A',
                    'customer_address' => 'N/A',
                    'notes' => 'Direct inquiry from product details page.',
                    'subtotal' => $product->price,
                    'shipping_fee' => $shippingCharge,
                    'total' => $total,
                    'status' => 'pending',
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                ]);
            });
        } catch (\Exception $e) {
            // Fallback quietly to WhatsApp redirect if database write fails to ensure checkout flow is not broken
        }

        $message = "🌐 *Product Inquiry*\n";
        if ($order) {
            $pdfUrl = route('order.pdf', $order->order_number);
            $message .= "*Lead Reference:* #{$order->order_number}\n";
            $message .= "📄 *Download Invoice PDF:* {$pdfUrl}\n\n";
        } else {
            $message .= "\n";
        }
        $message .= "Hello, I am interested in purchasing this product:\n\n";
        $message .= "🛒 *Product:* {$product->name}\n";
        $message .= "💵 *Price:* {$currency} {$product->price}\n";
        $message .= "🔗 *Link:* " . route('products.show', $product->slug) . "\n\n";
        $message .= "Please let me know if it is available. Thank you!";

        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
        return redirect($whatsappUrl);
    }

    /**
     * Full cart checkout/inquiry via WhatsApp.
     */
    public function inquireCart(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'phone' => 'required|string|max:191',
            'address' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $phone = env('WHATSAPP_ADMIN_NUMBER', '917979747352');
        $currency = env('CURRENCY_SYMBOL', '₹');
        
        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }
        
        // Dynamic Shipping calculations
        $shippingFeeSetting = (float) \App\Models\Setting::getValue('shipping_fee', 0);
        $freeShippingThreshold = (float) \App\Models\Setting::getValue('free_shipping_threshold', 0);
        $shippingCharge = $shippingFeeSetting;
        if ($freeShippingThreshold > 0 && $total >= $freeShippingThreshold) {
            $shippingCharge = 0;
        }
        $grandTotal = $total + $shippingCharge;

        // Transactional Order Creation
        $order = null;
        try {
            DB::transaction(function () use ($request, $cart, $total, $shippingCharge, $grandTotal, &$order) {
                $order = Order::create([
                    'customer_name' => $request->input('name'),
                    'customer_phone' => $request->input('phone'),
                    'customer_address' => $request->input('address'),
                    'notes' => $request->input('notes'),
                    'subtotal' => $total,
                    'shipping_fee' => $shippingCharge,
                    'total' => $grandTotal,
                    'status' => 'pending',
                ]);

                foreach ($cart as $id => $details) {
                    // Cart key can be '5_12' (variant) or '5' (simple) — extract numeric product_id
                    $productId = (int) explode('_', (string) $id)[0];
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $productId,
                        'product_name' => $details['name'],
                        'price'        => $details['price'],
                        'quantity'     => $details['quantity'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            \Log::error('Cart checkout failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to process order. Please try again.'], 500);
        }

        $pdfUrl = route('order.pdf', $order->order_number);

        // Build Concise WhatsApp Message
        $message = "*NEW ORDER*\n";
        $message .= "*Order Reference:* #{$order->order_number}\n";
        $message .= "*Download Invoice PDF:* {$pdfUrl}\n\n";
        $message .= "Hello, I have placed a new order. All details are attached in the PDF invoice above. Please confirm my order. Thank you!";

        // Clear the cart
        session()->forget('cart');

        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'pdf_url' => $pdfUrl,
                'whatsapp_url' => $whatsappUrl
            ]);
        }

        return redirect($whatsappUrl);
    }

    /**
     * Variant-based product inquiry: creates order from selected sizes/quantities,
     * generates PDF and returns JSON for auto-download + WhatsApp flow.
     */
    public function inquireVariants(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:191',
            'phone'      => 'required|string|max:191',
            'address'    => 'required|string',
            'variants'   => 'required|array|min:1',
        ]);

        $product  = Product::findOrFail($request->product_id);
        $variants = $request->variants; // [{value_name, qty, price}]
        $phone    = env('WHATSAPP_ADMIN_NUMBER', '917979747352');
        $currency = env('CURRENCY_SYMBOL', '₹');

        // Calculate totals
        $subtotal = 0;
        foreach ($variants as $v) {
            $subtotal += ($v['price'] ?? $product->price) * ($v['qty'] ?? 0);
        }

        $shippingFeeSetting     = (float) \App\Models\Setting::getValue('shipping_fee', 0);
        $freeShippingThreshold  = (float) \App\Models\Setting::getValue('free_shipping_threshold', 0);
        $shippingCharge = ($freeShippingThreshold > 0 && $subtotal >= $freeShippingThreshold) ? 0 : $shippingFeeSetting;
        $grandTotal = $subtotal + $shippingCharge;

        $order = null;
        try {
            DB::transaction(function () use ($request, $product, $variants, $subtotal, $shippingCharge, $grandTotal, &$order) {
                $order = Order::create([
                    'customer_name'    => $request->input('name'),
                    'customer_phone'   => $request->input('phone'),
                    'customer_address' => $request->input('address'),
                    'notes'            => $request->input('notes', ''),
                    'subtotal'         => $subtotal,
                    'shipping_fee'     => $shippingCharge,
                    'total'            => $grandTotal,
                    'status'           => 'pending',
                ]);

                // Each size variant becomes its own OrderItem line
                foreach ($variants as $v) {
                    $qty = (int) ($v['qty'] ?? 0);
                    if ($qty <= 0) continue;
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $product->id,
                        'product_name' => $product->name . ' [' . ($v['value_name'] ?? '') . ']',
                        'price'        => $v['price'] ?? $product->price,
                        'quantity'     => $qty,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create order. Please try again.'], 500);
        }

        $pdfUrl = route('order.pdf', $order->order_number);

        $message  = "*NEW VARIANT ORDER*\n";
        $message .= "*Order Reference:* #{$order->order_number}\n";
        $message .= "*Download Invoice PDF:* {$pdfUrl}\n\n";
        $message .= "Hello, I have placed a new order with size/variant details. All details are in the PDF invoice. Please confirm. Thank you!";

        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        return response()->json([
            'success'      => true,
            'pdf_url'      => $pdfUrl,
            'whatsapp_url' => $whatsappUrl,
        ]);
    }
}
