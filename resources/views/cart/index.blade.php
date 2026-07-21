@extends('layouts.app')

@section('title', 'Shopping Cart - Apna Mobile Hub')

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
                    <div class="cart-item-row" data-id="{{ $id }}" data-price="{{ $details['price'] }}">
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
                                <button type="button" class="qty-btn decrement-qty-btn" onclick="changeQtyBy('{{ $id }}', -1)">-</button>
                                <input type="number" 
                                       id="qty-input-{{ $id }}" 
                                       class="qty-input" 
                                       value="{{ $details['quantity'] }}" 
                                       min="1" 
                                       max="9999"
                                       oninput="onQtyInput('{{ $id }}')"
                                       onchange="onQtyInput('{{ $id }}')" 
                                       onkeydown="if(event.key === 'Enter') { this.blur(); }">
                                <button type="button" class="qty-btn increment-qty-btn" onclick="changeQtyBy('{{ $id }}', 1)">+</button>
                            </div>
                        </div>

                        <!-- Subtotal -->
                        <div class="cart-item-subtotal-col">
                            <span class="cart-item-subtotal">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                        </div>

                        <!-- Remove Button -->
                        <div class="cart-item-remove-col">
                            <button class="cart-remove-btn" onclick="removeFromCart('{{ $id }}')" title="Remove Item">
                                <i class="fa-solid fa-circle"></i>
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
                <div class="summary-card" data-shipping-fee="{{ $shippingFeeSetting }}" data-free-threshold="{{ $freeShippingThreshold }}">
                    <h3 class="summary-card-title">Order Summary</h3>
                    
                    <div class="summary-row">
                        <span>Items Total</span>
                        <span id="cart-items-total-display">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span id="cart-shipping-display">
                            @if($shippingCharge > 0)
                                <span class="shipping-charge-amount">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($shippingCharge, 2) }}</span>
                            @else
                                <span class="free-shipping">FREE</span>
                            @endif
                        </span>
                    </div>
                    
                    <div id="free-shipping-promo-wrapper">
                        @if($shippingCharge > 0 && $freeShippingThreshold > 0)
                            <div class="free-shipping-promo-box" style="margin: 10px 0 15px; padding: 10px 14px; background-color: #FFF9E6; border-radius: var(--radius-sm); border: 1px solid #FFE399; font-size: 12px; color: #8A6D1C; text-align: center; line-height: 1.4; font-weight: 500;">
                                Add <strong id="free-shipping-diff">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($freeShippingThreshold - $total, 2) }}</strong> more to get <strong>FREE SHIPPING!</strong>
                            </div>
                        @endif
                    </div>

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
                            
                            <button type="submit" class="btn-checkout-whatsapp" id="btn-checkout-whatsapp" @if($grandTotal < 500) disabled style="opacity: 0.5; cursor: not-allowed;" title="Minimum order value is 500" @endif>
                                <i class="fa-solid fa-message" class="wa-icon"></i> Place Order via WhatsApp
                            </button>
                            <p id="min-order-warning" style="font-size: 11px; color: #ef4444; text-align: center; margin-top: 8px; font-weight: 700; display: {{ $grandTotal < 500 ? 'block' : 'none' }};">Minimum order value must be ₹500.</p>
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
    let updateTimeout = null;

    // Real-time instant calculation on typing
    function onQtyInput(id) {
        const input = document.getElementById('qty-input-' + id);
        if (!input) return;

        let qtyVal = input.value;
        let qty = parseInt(qtyVal);
        
        if (isNaN(qty) || qty < 1) {
            qty = 0;
        }

        const row = document.querySelector('.cart-item-row[data-id="' + id + '"]');
        if (row) {
            const price = parseFloat(row.getAttribute('data-price')) || 0;
            const itemSubtotal = price * qty;
            const subtotalEl = row.querySelector('.cart-item-subtotal');
            if (subtotalEl) {
                subtotalEl.innerText = '₹ ' + itemSubtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }

        recalculateCartTotals();

        // Background debounced session update
        clearTimeout(updateTimeout);
        if (qty > 0) {
            updateTimeout = setTimeout(() => {
                syncCartQuantity(id, qty);
            }, 300);
        }
    }

    // Change quantity via + or - buttons
    function changeQtyBy(id, delta) {
        const input = document.getElementById('qty-input-' + id);
        if (!input) return;

        let current = parseInt(input.value) || 1;
        let newQty = current + delta;

        if (newQty < 1) {
            removeFromCart(id);
            return;
        }

        input.value = newQty;
        onQtyInput(id);
    }

    // Instant client-side recalculation of entire summary card
    function recalculateCartTotals() {
        let itemsTotal = 0;
        document.querySelectorAll('.cart-item-row').forEach(row => {
            const price = parseFloat(row.getAttribute('data-price')) || 0;
            const id = row.getAttribute('data-id');
            const input = document.getElementById('qty-input-' + id);
            let qty = parseInt(input ? input.value : 0);
            if (isNaN(qty) || qty < 0) qty = 0;
            itemsTotal += price * qty;
        });

        const summaryCard = document.querySelector('.summary-card');
        if (!summaryCard) return;

        const shippingFeeSetting = parseFloat(summaryCard.getAttribute('data-shipping-fee')) || 0;
        const freeShippingThreshold = parseFloat(summaryCard.getAttribute('data-free-threshold')) || 0;

        let shippingCharge = shippingFeeSetting;
        if (freeShippingThreshold > 0 && itemsTotal >= freeShippingThreshold) {
            shippingCharge = 0;
        }
        const grandTotal = itemsTotal + shippingCharge;

        // Update Items Total Display
        const itemsTotalEl = document.getElementById('cart-items-total-display');
        if (itemsTotalEl) {
            itemsTotalEl.innerText = '₹ ' + itemsTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Update Shipping Charge Display
        const shippingEl = document.getElementById('cart-shipping-display');
        if (shippingEl) {
            if (shippingCharge > 0) {
                shippingEl.innerHTML = '<span class="shipping-charge-amount">₹ ' + shippingCharge.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</span>';
            } else {
                shippingEl.innerHTML = '<span class="free-shipping">FREE</span>';
            }
        }

        // Update Free Shipping Banner
        const promoWrapper = document.getElementById('free-shipping-promo-wrapper');
        if (promoWrapper) {
            if (shippingCharge > 0 && freeShippingThreshold > 0 && itemsTotal < freeShippingThreshold) {
                const diff = freeShippingThreshold - itemsTotal;
                promoWrapper.innerHTML = '<div class="free-shipping-promo-box" style="margin: 10px 0 15px; padding: 10px 14px; background-color: #FFF9E6; border-radius: var(--radius-sm); border: 1px solid #FFE399; font-size: 12px; color: #8A6D1C; text-align: center; line-height: 1.4; font-weight: 500;">Add <strong>₹ ' + diff.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong> more to get <strong>FREE SHIPPING!</strong></div>';
            } else {
                promoWrapper.innerHTML = '';
            }
        }

        // Update Grand Total Display
        const grandTotalEl = document.querySelector('.summary-total-price');
        if (grandTotalEl) {
            grandTotalEl.innerText = '₹ ' + grandTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Update WhatsApp Order Submit Button state
        const submitBtn = document.getElementById('btn-checkout-whatsapp');
        const minOrderMsg = document.getElementById('min-order-warning');
        if (submitBtn) {
            if (grandTotal < 500) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
                if (minOrderMsg) minOrderMsg.style.display = 'block';
            } else {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                if (minOrderMsg) minOrderMsg.style.display = 'none';
            }
        }
    }

    // Sync quantity to backend session
    function syncCartQuantity(id, quantity) {
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
                window.location.reload();
            });
        }
    }

    // AJAX Checkout Form Submission with Auto-Download & WhatsApp Tab Open
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.querySelector('.checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = document.getElementById('btn-checkout-whatsapp');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.7';
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating Invoice...';
                }

                const formData = new FormData(form);

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    // Parse response as text first, then try JSON
                    const text = await res.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch(e) {
                        console.error('Non-JSON response:', text.substring(0, 300));
                        throw new Error('Server returned unexpected response. Please try again.');
                    }

                    if (data.success) {
                        // 1. Auto-download PDF Invoice on user's device
                        const link = document.createElement('a');
                        link.href = data.pdf_url;
                        link.target = '_blank';
                        link.download = '';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // 2. Open WhatsApp pre-filled window/tab
                        window.open(data.whatsapp_url, '_blank');

                        // 3. Reload cart after short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                    } else {
                        alert(data.message || 'Failed to process order. Please try again.');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.style.opacity = '1';
                            submitBtn.innerHTML = '<i class="fa-solid fa-message"></i> Place Order via WhatsApp';
                        }
                    }
                } catch (err) {
                    alert(err.message || 'An error occurred. Please try again.');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                        submitBtn.innerHTML = '<i class="fa-solid fa-message"></i> Place Order via WhatsApp';
                    }
                }
            });
        }
    });
</script>
@endsection
