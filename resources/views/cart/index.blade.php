@extends('layouts.app')

@section('title', 'Shopping Cart - LuxeShop')

@section('content')
<div class="container">
    <h1 class="page-main-title">Shopping Cart</h1>

    @if(count($cart) > 0)
        <div class="cart-layout">
            <!-- Cart Items List -->
            <div class="cart-items-section">
                @php $total = 0; @endphp
                @foreach($cart as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <div class="cart-item-row" data-id="{{ $id }}">
                        <div class="cart-item-img-col">
                            <img src="{{ $details['image_path'] }}" alt="{{ $details['name'] }}" class="cart-item-img">
                        </div>
                        
                        <div class="cart-item-details-col">
                            <span class="cart-item-name">
                                <a href="{{ route('products.show', $details['slug']) }}">{{ $details['name'] }}</a>
                            </span>
                            <span class="cart-item-price">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($details['price'], 2) }}</span>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="cart-item-quantity-col">
                            <div class="quantity-picker">
                                <button class="qty-btn decrement-qty-btn" onclick="updateQuantity({{ $id }}, {{ $details['quantity'] - 1 }})">-</button>
                                <span class="qty-display">{{ $details['quantity'] }}</span>
                                <button class="qty-btn increment-qty-btn" onclick="updateQuantity({{ $id }}, {{ $details['quantity'] + 1 }})">+</button>
                            </div>
                        </div>

                        <!-- Subtotal -->
                        <div class="cart-item-subtotal-col">
                            <span class="cart-item-subtotal">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                        </div>

                        <!-- Remove Button -->
                        <div class="cart-item-remove-col">
                            <button class="cart-remove-btn" onclick="removeFromCart({{ $id }})" title="Remove Item">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary & WhatsApp Form -->
            @php
                $shippingFeeSetting = (float) \App\Models\Setting::getValue('shipping_fee', 0);
                $freeShippingThreshold = (float) \App\Models\Setting::getValue('free_shipping_threshold', 0);
                
                $shippingCharge = $shippingFeeSetting;
                if ($freeShippingThreshold > 0 && $total >= $freeShippingThreshold) {
                    $shippingCharge = 0;
                }
                $grandTotal = $total + $shippingCharge;
            @endphp
            <div class="cart-summary-section">
                <div class="summary-card">
                    <h3 class="summary-card-title">Order Summary</h3>
                    
                    <div class="summary-row">
                        <span>Items Total</span>
                        <span>{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        @if($shippingCharge > 0)
                            <span class="shipping-charge-amount">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($shippingCharge, 2) }}</span>
                        @else
                            <span class="free-shipping">FREE</span>
                        @endif
                    </div>
                    
                    @if($shippingCharge > 0 && $freeShippingThreshold > 0)
                        <div class="free-shipping-promo-box" style="margin: 10px 0 15px; padding: 10px 14px; background-color: #FFF9E6; border-radius: var(--radius-sm); border: 1px solid #FFE399; font-size: 12px; color: #8A6D1C; text-align: center; line-height: 1.4; font-weight: 500;">
                            Add <strong>{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($freeShippingThreshold - $total, 2) }}</strong> more to get <strong>FREE SHIPPING!</strong>
                        </div>
                    @endif

                    <hr class="separator">
                    
                    <div class="summary-row total-row">
                        <span>Total</span>
                        <span class="summary-total-price">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <!-- WhatsApp Checkout Form -->
                    <div class="whatsapp-checkout-form-container">
                        <h4 class="form-title">⚡ Order Details (WhatsApp Inquiry)</h4>
                        <form action="{{ route('cart.inquireCart') }}" method="POST" class="checkout-form">
                            @csrf
                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" id="name" name="name" required class="form-control" placeholder="e.g. John Doe">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" required class="form-control" placeholder="e.g. +1 234 567 890">
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Delivery Address</label>
                                <textarea id="address" name="address" required class="form-control" rows="3" placeholder="e.g. Street 12, Apt 4B, New York"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Special Notes (Optional)</label>
                                <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="e.g. Please deliver after 5 PM"></textarea>
                            </div>
                            
                            <button type="submit" class="btn-checkout-whatsapp">
                                <i data-lucide="message-circle" class="wa-icon"></i> Place Order via WhatsApp
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="empty-state">
            <span class="empty-emoji">🛒</span>
            <h3>Your Cart is Empty</h3>
            <p>Looks like you haven't added any products to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="btn-primary">Browse Products</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Update Cart Quantity
    function updateQuantity(id, quantity) {
        if(quantity < 1) {
            removeFromCart(id);
            return;
        }
        
        fetch('{{ route('cart.update') }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                id: id,
                quantity: quantity
            })
        })
        .then(response => {
            // Reload page to reflect changes
            window.location.reload();
        });
    }

    // Remove item from Cart
    function removeFromCart(id) {
        if(confirm('Are you sure you want to remove this item?')) {
            fetch('{{ route('cart.remove') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: id
                })
            })
            .then(response => {
                // Reload page to reflect changes
                window.location.reload();
            });
        }
    }
</script>
@endsection
