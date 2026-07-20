@php
    $pendingOrders = \App\Models\Order::where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    $latestPendingOrderTime = $pendingOrders->first() ? $pendingOrders->first()->created_at->toIso8601String() : '';
    $totalPendingCount = $pendingOrders->count();
@endphp

<div class="sedap-topbar-icons" style="display: flex; align-items: center; gap: 14px; margin-left: 30px; margin-right: 15px; font-family: 'Outfit', sans-serif; position: relative;">
    <!-- Bell Notification -->
    <div id="notifications-trigger" onclick="toggleNotifications(event)" style="position: relative; width: 36px; height: 36px; border-radius: 12px; background-color: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#DBEAFE'" onmouseout="this.style.backgroundColor='#EFF6FF'">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        <span id="notifications-badge" style="position: absolute; top: -4px; right: -4px; background-color: #3B82F6; color: #ffffff; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 10px; border: 2px solid #ffffff; line-height: 1; display: none;">0</span>
    </div>

    <!-- Gear settings (Redirect to settings page index) -->
    <a href="{{ \App\Filament\Resources\SettingResource::getUrl('index') }}" style="text-decoration: none; display: block;">
        <div style="position: relative; width: 36px; height: 36px; border-radius: 12px; background-color: #FEE2E2; color: #EF4444; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#FCA5A5'" onmouseout="this.style.backgroundColor='#FEE2E2'">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </div>
    </a>

    <!-- Custom Dropdown Menu for Pending Orders -->
    <div id="notifications-dropdown" style="display: none; position: absolute; top: 45px; right: 50px; width: 320px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; z-index: 10000; padding: 10px 0;">
        <div style="padding: 10px 16px; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #1e293b; font-size: 14px; display: flex; justify-content: space-between; align-items: center;">
            <span>New Orders</span>
            <span style="font-size: 11px; background-color: #2563EB; color: white; padding: 2px 8px; border-radius: 10px;">{{ $totalPendingCount }} Pending</span>
        </div>
        
        <div style="max-height: 240px; overflow-y: auto;">
            @forelse($pendingOrders as $order)
                <a href="{{ \App\Filament\Resources\OrderResource::getUrl('edit', ['record' => $order->id]) }}" style="display: block; padding: 12px 16px; text-decoration: none; color: #334155; border-bottom: 1px solid #f8fafc; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <span style="font-weight: 700; color: #2563EB; font-size: 13px;">{{ $order->order_number }}</span>
                        <span style="font-size: 11px; color: #64748b;">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                        <span>Customer: {{ $order->customer_name }}</span>
                        <span style="font-weight: 700;">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </a>
            @empty
                <div style="padding: 20px; text-align: center; color: #64748b; font-size: 13px;">
                    No pending orders
                </div>
            @endforelse
        </div>
        
        <div style="padding: 10px 16px; border-top: 1px solid #f1f5f9; text-align: center;">
            <a href="{{ \App\Filament\Resources\OrderResource::getUrl('index') }}" style="font-size: 12px; color: #2563EB; text-decoration: none; font-weight: 600;">View All Orders</a>
        </div>
    </div>
</div>

<script>
    function toggleNotifications(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('notifications-dropdown');
        if (!dropdown) return;
        
        const isVisible = dropdown.style.display === 'block';
        dropdown.style.display = isVisible ? 'none' : 'block';
        
        if (!isVisible) {
            // Set localStorage to mark notifications as checked up to current newest order
            const latestTime = "{{ $latestPendingOrderTime }}";
            if (latestTime) {
                localStorage.setItem('notifications_read_until', latestTime);
            }
            const badge = document.getElementById('notifications-badge');
            if (badge) {
                badge.style.display = 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dynamic count notification badge from localStorage
        const readUntil = localStorage.getItem('notifications_read_until');
        const pendingOrders = @json($pendingOrders->map(fn($o) => ['id' => $o->id, 'created_at' => $o->created_at->toIso8601String()]));
        
        let newCount = 0;
        if (!readUntil) {
            newCount = pendingOrders.length;
        } else {
            const readTime = new Date(readUntil).getTime();
            pendingOrders.forEach(o => {
                if (new Date(o.created_at).getTime() > readTime) {
                    newCount++;
                }
            });
        }
        
        const badge = document.getElementById('notifications-badge');
        if (badge) {
            if (newCount > 0) {
                badge.innerText = newCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Close notifications dropdown on click outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notifications-dropdown');
            const trigger = document.getElementById('notifications-trigger');
            if (dropdown && dropdown.style.display === 'block') {
                if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            }
        });
    });
</script>
