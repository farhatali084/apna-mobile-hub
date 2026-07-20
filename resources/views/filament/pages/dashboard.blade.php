<div class="sedap-dashboard-wrapper" wire:poll.15s>
    <!-- Load Chart.js and Fonts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Theme Overrides for Dashboard Layout -->
    <style>
        .sedap-dashboard-wrapper {
            font-family: 'Outfit', sans-serif;
            color: #111827;
            background-color: #F3F4F6;
            margin-top: 15px;
        }

        /* Welcome header block */
        .sedap-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .sedap-welcome-text h1 {
            font-size: 28px;
            font-weight: 800;
            color: #111827;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }

        .sedap-welcome-text p {
            font-size: 13px;
            color: #9CA3AF;
            margin: 4px 0 0 0;
            font-weight: 500;
        }

        /* Filter period card */
        .sedap-filter-period {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #E5E7EB;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
        }

        .sedap-filter-period:hover {
            border-color: #2563EB;
        }

        .sedap-filter-period-icon {
            color: #2563EB;
            display: flex;
            align-items: center;
        }

        .sedap-filter-period-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .sedap-filter-period-text span:first-child {
            font-size: 11px;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sedap-filter-period-text span:last-child {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
        }

        /* Stat Cards Grid */
        .sedap-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .sedap-stat-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 10px rgba(0,0,0,0.01);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .sedap-stat-card:hover {
            transform: translateY(-2px);
        }

        .sedap-stat-icon-wrapper {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background-color: #EFF6FF;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563EB;
            flex-shrink: 0;
        }

        .sedap-stat-icon-wrapper.canceled {
            background-color: #FEE2E2;
            color: #EF4444;
        }

        .sedap-stat-info {
            display: flex;
            flex-direction: column;
        }

        .sedap-stat-value {
            font-size: 28px;
            font-weight: 900;
            color: #111827;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.1;
        }

        .sedap-stat-label {
            font-size: 13px;
            font-weight: 600;
            color: #9CA3AF;
            margin-top: 2px;
        }

        .sedap-stat-trend {
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 3px;
            margin-top: 4px;
        }

        .sedap-stat-trend.up {
            color: #2563EB;
        }

        .sedap-stat-trend.down {
            color: #EF4444;
        }

        /* Charts Row 1: Pie and Line */
        .sedap-charts-row-one {
            display: grid;
            grid-template-columns: 1.1fr 1.9fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 1024px) {
            .sedap-charts-row-one {
                grid-template-columns: 1fr;
            }
        }

        .sedap-chart-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 10px rgba(0,0,0,0.01);
        }

        .sedap-chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .sedap-chart-title {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
            font-family: 'Montserrat', sans-serif;
        }

        /* Pie chart specific subcontrols */
        .pie-controls {
            display: flex;
            gap: 15px;
            font-size: 12px;
            font-weight: 700;
            color: #9CA3AF;
            align-items: center;
        }

        .pie-control-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pie-control-item input {
            accent-color: #2563EB;
            width: 14px;
            height: 14px;
        }

        .pie-radial-charts {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 180px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pie-radial-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .radial-name {
            font-size: 12px;
            font-weight: 700;
            color: #9CA3AF;
            margin-top: 10px;
            text-align: center;
        }

        /* Order line chart specific styles */
        .btn-save-report {
            background-color: #ffffff;
            border: 1px solid #2563EB;
            color: #2563EB;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-save-report:hover {
            background-color: #2563EB;
            color: #ffffff;
        }

        /* Charts Row 2: Total Revenue double line and Customer map bar chart */
        .sedap-charts-row-two {
            display: grid;
            grid-template-columns: 1.6fr 1.4fr;
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .sedap-charts-row-two {
                grid-template-columns: 1fr;
            }
        }

        .revenue-legend {
            display: flex;
            gap: 15px;
            font-size: 11px;
            font-weight: 700;
            color: #9CA3AF;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-color-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .legend-color-dot.blue { background-color: #3B82F6; }
        .legend-color-dot.red { background-color: #EF4444; }

        .weekly-dropdown {
            background-color: #ffffff;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            color: #374151;
            outline: none;
            cursor: pointer;
        }

        .weekly-dropdown:hover {
            border-color: #2563EB;
        }
    </style>

    <!-- 1. Welcoming Header Row -->
    <div class="sedap-header-row">
        <div class="sedap-welcome-text">
            <h1>Dashboard</h1>
            <p>Hi, {{ auth()->user()->name ?? 'Samantha' }}. Welcome back to Apna Mobile Hub Admin!</p>
        </div>
        
        <div class="sedap-filter-period" style="padding: 10px 18px; display: flex; align-items: center; gap: 10px; cursor: pointer;">
            <div class="sedap-filter-period-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            </div>
            <div class="sedap-filter-period-text" style="display: flex; flex-direction: column; line-height: 1.2;">
                <span>Filter Periode</span>
                <select wire:model.live="periodDays" style="border: none; background: transparent; font-size: 12px; font-weight: 600; color: #374151; padding: 0; margin: 0; outline: none; cursor: pointer; width: auto; min-width: 105px;">
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="365">Last 365 Days</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 2. Stat Cards Grid -->
    <div class="sedap-stats-grid">
        <!-- Card 1: Total Orders -->
        <div class="sedap-stat-card">
            <div class="sedap-stat-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 6v12"/></svg>
            </div>
            <div class="sedap-stat-info">
                <span class="sedap-stat-value">{{ $totalOrders }}</span>
                <span class="sedap-stat-label">Total Orders</span>
                <span class="sedap-stat-trend {{ $ordersChange >= 0 ? 'up' : 'down' }}">
                    @if($ordersChange >= 0)
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                    @endif
                    {{ abs($ordersChange) }}% ({{ $periodDays }} days)
                </span>
            </div>
        </div>

        <!-- Card 2: Total Delivered -->
        <div class="sedap-stat-card">
            <div class="sedap-stat-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package"><path d="M11 21.88a2 2 0 0 0 2 0l8.3-4.7a2 2 0 0 0 1-1.7V5.5a2 2 0 0 0-1-1.7l-8.3-4.7a2 2 0 0 0-2 0L2.7 3.8a2 2 0 0 0-1 1.7v10.4a2 2 0 0 0 1 1.7Z"/><path d="M12 22V12"/><path d="m21.8 7.9-8.3 4.9a2 2 0 0 1-2 0L3.2 7.9"/><path d="m17 4.8-8.4 5"/></svg>
            </div>
            <div class="sedap-stat-info">
                <span class="sedap-stat-value">{{ $totalDelivered }}</span>
                <span class="sedap-stat-label">Total Delivered</span>
                <span class="sedap-stat-trend {{ $deliveredChange >= 0 ? 'up' : 'down' }}">
                    @if($deliveredChange >= 0)
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                    @endif
                    {{ abs($deliveredChange) }}% ({{ $periodDays }} days)
                </span>
            </div>
        </div>

        <!-- Card 3: Total Canceled -->
        <div class="sedap-stat-card">
            <div class="sedap-stat-icon-wrapper canceled">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-x"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="m15 11-6 6"/><path d="m9 11 6 6"/></svg>
            </div>
            <div class="sedap-stat-info">
                <span class="sedap-stat-value">{{ $totalCanceled }}</span>
                <span class="sedap-stat-label">Total Canceled</span>
                <span class="sedap-stat-trend {{ $canceledChange <= 0 ? 'up' : 'down' }}"> <!-- Positive increase in cancels is downward trend visually -->
                    @if($canceledChange <= 0)
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                    @endif
                    {{ abs($canceledChange) }}% ({{ $periodDays }} days)
                </span>
            </div>
        </div>

        <!-- Card 4: Total Revenue -->
        <div class="sedap-stat-card">
            <div class="sedap-stat-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <div class="sedap-stat-info">
                <span class="sedap-stat-value">{{ $formattedRevenue }}</span>
                <span class="sedap-stat-label">Total Revenue</span>
                <span class="sedap-stat-trend {{ $revenueChange >= 0 ? 'up' : 'down' }}">
                    @if($revenueChange >= 0)
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                    @endif
                    {{ abs($revenueChange) }}% ({{ $periodDays }} days)
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Charts Row One -->
    <div class="sedap-charts-row-one">
        <!-- Pie Chart Box -->
        <div class="sedap-chart-card">
            <div class="sedap-chart-header">
                <span class="sedap-chart-title">Ratios</span>
                <div class="pie-controls">
                    <label class="pie-control-item">
                        <input type="checkbox" checked disabled>
                        Live
                    </label>
                </div>
            </div>
            
            <div class="pie-radial-charts">
                <!-- Radial 1: Total Order -->
                <div class="pie-radial-item">
                    <div style="width: 80px; height: 80px; position: relative;">
                        <canvas id="totalOrderChart" wire:ignore style="width: 100%; height: 100%;"></canvas>
                        <span class="percentage-label" id="totalOrderLabel" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0; font-size: 14px; font-weight: 800; color: #111827; font-family: 'Montserrat', sans-serif;">{{ $deliveredPercent }}%</span>
                    </div>
                    <span class="radial-name" style="margin-top: 10px;">Total Order</span>
                </div>

                <!-- Radial 2: Customer Growth -->
                <div class="pie-radial-item">
                    <div style="width: 80px; height: 80px; position: relative;">
                        <canvas id="customerGrowthChart" wire:ignore style="width: 100%; height: 100%;"></canvas>
                        <span class="percentage-label" id="customerGrowthLabel" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0; font-size: 14px; font-weight: 800; color: #111827; font-family: 'Montserrat', sans-serif;">{{ $customerGrowthPercent }}%</span>
                    </div>
                    <span class="radial-name" style="margin-top: 10px;">Customer Growth</span>
                </div>

                <!-- Radial 3: Total Revenue -->
                <div class="pie-radial-item">
                    <div style="width: 80px; height: 80px; position: relative;">
                        <canvas id="totalRevenueChart" wire:ignore style="width: 100%; height: 100%;"></canvas>
                        <span class="percentage-label" id="totalRevenueLabel" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0; font-size: 14px; font-weight: 800; color: #111827; font-family: 'Montserrat', sans-serif;">{{ $revenuePercent }}%</span>
                    </div>
                    <span class="radial-name" style="margin-top: 10px;">Today's Target</span>
                </div>
            </div>
        </div>

        <!-- Line Chart: Chart Order -->
        <div class="sedap-chart-card">
            <div class="sedap-chart-header">
                <div>
                    <span class="sedap-chart-title">Chart Order</span>
                    <div style="font-size: 11px; color: #9CA3AF; font-weight: 500; margin-top: 2px;">Weekly total orders distribution</div>
                </div>
                <button class="btn-save-report">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-to-line"><path d="M12 17V3"/><path d="m6 11 6 6 6-6"/><path d="M19 21H5"/></svg>
                    Save Report
                </button>
            </div>
            
            <div id="orderChartContainer" data-labels="{{ $orderChartLabelsJson }}" data-values="{{ $orderChartDataJson }}" style="height: 180px; position: relative;">
                <canvas id="orderChartCanvas" wire:ignore style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- 4. Charts Row Two -->
    <div class="sedap-charts-row-two">
        <!-- Total Revenue Line Chart -->
        <div class="sedap-chart-card">
            <div class="sedap-chart-header">
                <span class="sedap-chart-title">Total Revenue</span>
                <div class="revenue-legend">
                    <div class="legend-item">
                        <span class="legend-color-dot blue"></span>
                        {{ $lastYear }}
                    </div>
                    <div class="legend-item">
                        <span class="legend-color-dot red"></span>
                        {{ $currentYear }}
                    </div>
                </div>
            </div>
            
            <div id="revenueChartContainer" data-lastyear="{{ $revenueLastYearJson }}" data-currentyear="{{ $revenueCurrentYearJson }}" style="height: 250px; position: relative;">
                <canvas id="revenueChartCanvas" wire:ignore style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>

        <!-- Customer Map Bar Chart -->
        <div class="sedap-chart-card">
            <div class="sedap-chart-header">
                <span class="sedap-chart-title">Customer Map</span>
                <select class="weekly-dropdown">
                    <option value="weekly">Weekly</option>
                </select>
            </div>
            
            <div id="customerChartContainer" data-currentweek="{{ $currentWeekDataJson }}" data-lastweek="{{ $lastWeekDataJson }}" style="height: 250px; position: relative;">
                <canvas id="customerChartCanvas" wire:ignore style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- ChartJS Render Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Helper to get data attributes from elements safely
            const getJsonData = (id, attribute) => {
                const el = document.getElementById(id);
                return JSON.parse(el ? el.getAttribute(attribute) : '[]');
            };

            // 1. Chart 1: Order Chart Canvas
            const ctxOrder = document.getElementById('orderChartCanvas').getContext('2d');
            
            const gradientOrder = ctxOrder.createLinearGradient(0, 0, 0, 180);
            gradientOrder.addColorStop(0, 'rgba(47, 128, 237, 0.25)');
            gradientOrder.addColorStop(1, 'rgba(255, 255, 255, 0)');
            
            window.orderChart = new Chart(ctxOrder, {
                type: 'line',
                data: {
                    labels: getJsonData('orderChartContainer', 'data-labels'),
                    datasets: [{
                        label: 'Orders',
                        data: getJsonData('orderChartContainer', 'data-values'),
                        borderColor: '#2F80ED',
                        backgroundColor: gradientOrder,
                        fill: true,
                        tension: 0.45,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2F80ED',
                        pointBorderWidth: 2,
                        pointRadius: [0, 0, 0, 6, 0, 0, 0], // Peak styling
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Outfit', weight: 600 }, color: '#9CA3AF' } },
                        y: { display: false }
                    }
                },
                plugins: [{
                    id: 'customOrderTooltip',
                    afterDatasetsDraw: function(chart) {
                        const ctx = chart.ctx;
                        const meta = chart.getDatasetMeta(0);
                        const index = 3; // Wednesday (Peak index)
                        const point = meta.data[index];
                        if (!point) return;
                        
                        const x = point.x;
                        const y = point.y;
                        
                        ctx.save();
                        
                        // 1. Draw vertical dashed line to bottom
                        ctx.beginPath();
                        ctx.setLineDash([4, 4]);
                        ctx.moveTo(x, y + 5);
                        ctx.lineTo(x, chart.chartArea.bottom);
                        ctx.strokeStyle = 'rgba(47, 128, 237, 0.4)';
                        ctx.lineWidth = 1.5;
                        ctx.stroke();
                        
                        // 2. Draw glowing dot
                        ctx.beginPath();
                        ctx.arc(x, y, 10, 0, 2 * Math.PI);
                        ctx.fillStyle = 'rgba(47, 128, 237, 0.15)';
                        ctx.fill();
                        
                        ctx.beginPath();
                        ctx.arc(x, y, 5, 0, 2 * Math.PI);
                        ctx.fillStyle = '#2F80ED';
                        ctx.fill();
                        
                        // 3. Draw tooltip container
                        const tooltipWidth = 110;
                        const tooltipHeight = 44;
                        const rectX = x - tooltipWidth / 2;
                        const rectY = y - tooltipHeight - 16;
                        
                        // Tooltip shadow
                        ctx.shadowColor = 'rgba(17, 24, 39, 0.08)';
                        ctx.shadowBlur = 10;
                        ctx.shadowOffsetX = 0;
                        ctx.shadowOffsetY = 4;
                        
                        // Rounded rectangle for tooltip
                        ctx.fillStyle = '#ffffff';
                        ctx.beginPath();
                        if (ctx.roundRect) {
                            ctx.roundRect(rectX, rectY, tooltipWidth, tooltipHeight, 8);
                        } else {
                            ctx.rect(rectX, rectY, tooltipWidth, tooltipHeight);
                        }
                        ctx.fill();
                        ctx.restore();
                        
                        // Draw tooltip border
                        ctx.save();
                        ctx.strokeStyle = '#E5E7EB';
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        if (ctx.roundRect) {
                            ctx.roundRect(rectX, rectY, tooltipWidth, tooltipHeight, 8);
                        } else {
                            ctx.rect(rectX, rectY, tooltipWidth, tooltipHeight);
                        }
                        ctx.stroke();
                        
                        // Draw text
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        
                        // First line text
                        ctx.font = 'bold 11px Outfit';
                        ctx.fillStyle = '#111827';
                        const wedsVal = chart.data.datasets[0].data[3] || 0;
                        ctx.fillText(wedsVal + ' Orders', x, rectY + 15);
                        
                        // Second line text
                        ctx.font = '600 9px Outfit';
                        ctx.fillStyle = '#9CA3AF';
                        ctx.fillText('This Week', x, rectY + 28);
                        
                        ctx.restore();
                    }
                }]
            });

            // 2. Chart 2: Revenue Chart Canvas
            const ctxRevenue = document.getElementById('revenueChartCanvas').getContext('2d');
            window.revenueChart = new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Last Year',
                            data: getJsonData('revenueChartContainer', 'data-lastyear'),
                            borderColor: '#3B82F6',
                            borderWidth: 3,
                            fill: false,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Current Year',
                            data: getJsonData('revenueChartContainer', 'data-currentyear'),
                            borderColor: '#EF4444',
                            borderWidth: 3,
                            fill: false,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Outfit' }, color: '#9CA3AF' } },
                        y: { ticks: { callback: value => '₹' + value + 'k', font: { family: 'Outfit' }, color: '#9CA3AF' } }
                    }
                }
            });

            // 3. Chart 3: Customer Map Canvas
            const ctxCustomer = document.getElementById('customerChartCanvas').getContext('2d');
            window.customerChart = new Chart(ctxCustomer, {
                type: 'bar',
                data: {
                    labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    datasets: [
                        {
                            label: 'Last Week',
                            data: getJsonData('customerChartContainer', 'data-lastweek'),
                            backgroundColor: '#FBBF24',
                            borderRadius: 6
                        },
                        {
                            label: 'Current Week',
                            data: getJsonData('customerChartContainer', 'data-currentweek'),
                            backgroundColor: '#EF4444',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Outfit' }, color: '#9CA3AF' } },
                        y: { grid: { borderDash: [5, 5] }, ticks: { font: { family: 'Outfit' }, color: '#9CA3AF' } }
                    }
                }
            });

            // 4. Doughnut Progress Rings Helper
            const createDoughnut = (canvasId, color, percent) => {
                const ctx = document.getElementById(canvasId).getContext('2d');
                return new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [percent, Math.max(0, 100 - percent)],
                            backgroundColor: [color, '#E5E7EB'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '80%',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } }
                    }
                });
            };

            const getPercentVal = (id) => {
                const el = document.getElementById(id);
                return el ? (parseInt(el.innerText) || 0) : 0;
            };

            window.totalOrderDoughnut = createDoughnut('totalOrderChart', '#EF4444', getPercentVal('totalOrderLabel'));
            window.customerGrowthDoughnut = createDoughnut('customerGrowthChart', '#2563EB', getPercentVal('customerGrowthLabel'));
            window.totalRevenueDoughnut = createDoughnut('totalRevenueChart', '#3B82F6', getPercentVal('totalRevenueLabel'));

            // 5. Update chart datasets when Livewire polls
            const updateAllCharts = () => {
                // Order Chart
                const orderContainer = document.getElementById('orderChartContainer');
                if (orderContainer && window.orderChart) {
                    window.orderChart.data.labels = JSON.parse(orderContainer.getAttribute('data-labels') || '[]');
                    window.orderChart.data.datasets[0].data = JSON.parse(orderContainer.getAttribute('data-values') || '[]');
                    window.orderChart.update();
                }

                // Revenue Chart
                const revenueContainer = document.getElementById('revenueChartContainer');
                if (revenueContainer && window.revenueChart) {
                    window.revenueChart.data.datasets[0].data = JSON.parse(revenueContainer.getAttribute('data-lastyear') || '[]');
                    window.revenueChart.data.datasets[1].data = JSON.parse(revenueContainer.getAttribute('data-currentyear') || '[]');
                    window.revenueChart.update();
                }

                // Customer/Map Chart
                const customerContainer = document.getElementById('customerChartContainer');
                if (customerContainer && window.customerChart) {
                    window.customerChart.data.datasets[0].data = JSON.parse(customerContainer.getAttribute('data-lastweek') || '[]');
                    window.customerChart.data.datasets[1].data = JSON.parse(customerContainer.getAttribute('data-currentweek') || '[]');
                    window.customerChart.update();
                }

                // Doughnuts
                if (window.totalOrderDoughnut) {
                    const percent = getPercentVal('totalOrderLabel');
                    window.totalOrderDoughnut.data.datasets[0].data = [percent, Math.max(0, 100 - percent)];
                    window.totalOrderDoughnut.update();
                }
                if (window.customerGrowthDoughnut) {
                    const percent = getPercentVal('customerGrowthLabel');
                    window.customerGrowthDoughnut.data.datasets[0].data = [percent, Math.max(0, 100 - percent)];
                    window.customerGrowthDoughnut.update();
                }
                if (window.totalRevenueDoughnut) {
                    const percent = getPercentVal('totalRevenueLabel');
                    window.totalRevenueDoughnut.data.datasets[0].data = [percent, Math.max(0, 100 - percent)];
                    window.totalRevenueDoughnut.update();
                }
            };

            // Register Livewire commit hook
            if (window.Livewire) {
                Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                    succeed(() => {
                        setTimeout(updateAllCharts, 100);
                    });
                });
            } else {
                document.addEventListener('livewire:init', () => {
                    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                        succeed(() => {
                            setTimeout(updateAllCharts, 100);
                        });
                    });
                });
            }
        });
    </script>
</div>
