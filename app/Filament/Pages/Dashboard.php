<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    // Public properties that Livewire binds and re-renders on poll
    public int $totalOrders = 0;
    public int $totalDelivered = 0;
    public int $totalCanceled = 0;
    public string $formattedRevenue = '₹0';
    public int $periodDays = 30;

    public int $ordersChange = 0;
    public int $deliveredChange = 0;
    public int $canceledChange = 0;
    public int $revenueChange = 0;

    public int $deliveredPercent = 0;
    public int $revenuePercent = 0;
    public int $customerGrowthPercent = 0;

    public string $orderChartLabelsJson = '[]';
    public string $orderChartDataJson = '[]';
    public string $revenueLastYearJson = '[]';
    public string $revenueCurrentYearJson = '[]';
    public string $currentWeekDataJson = '[]';
    public string $lastWeekDataJson = '[]';
    
    public int $currentYear;
    public int $lastYear;

    public function mount(): void
    {
        $this->currentYear = now()->year;
        $this->lastYear = $this->currentYear - 1;
        $this->loadStats();
    }

    public function rendering(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        // Dynamic period calculation based on selected value
        $currentStart = now()->subDays($this->periodDays)->startOfDay();
        $currentEnd = now()->endOfDay();
        $priorStart = now()->subDays($this->periodDays * 2)->startOfDay();
        $priorEnd = now()->subDays($this->periodDays)->subSecond();

        // 1. STAT CARDS (Total / Delivered / Canceled / Revenue) via DB aggregation
        // Total Orders
        $currentOrdersCount = Order::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $priorOrdersCount = Order::whereBetween('created_at', [$priorStart, $priorEnd])->count();
        $this->totalOrders = Order::count();
        $this->ordersChange = $this->calculatePercentageChange($currentOrdersCount, $priorOrdersCount);

        // Delivered
        $currentDeliveredCount = Order::where('status', 'delivered')->whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $priorDeliveredCount = Order::where('status', 'delivered')->whereBetween('created_at', [$priorStart, $priorEnd])->count();
        $this->totalDelivered = Order::where('status', 'delivered')->count();
        $this->deliveredChange = $this->calculatePercentageChange($currentDeliveredCount, $priorDeliveredCount);

        // Canceled
        $currentCanceledCount = Order::where('status', 'canceled')->whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $priorCanceledCount = Order::where('status', 'canceled')->whereBetween('created_at', [$priorStart, $priorEnd])->count();
        $this->totalCanceled = Order::where('status', 'canceled')->count();
        $this->canceledChange = $this->calculatePercentageChange($currentCanceledCount, $priorCanceledCount);

        // Revenue (delivered total)
        $currentRevenueSum = (float) Order::where('status', 'delivered')->whereBetween('created_at', [$currentStart, $currentEnd])->sum('total');
        $priorRevenueSum = (float) Order::where('status', 'delivered')->whereBetween('created_at', [$priorStart, $priorEnd])->sum('total');
        $totalRevenueVal = (float) Order::where('status', 'delivered')->sum('total');
        $this->formattedRevenue = '₹' . number_format($totalRevenueVal, 0);
        $this->revenueChange = $this->calculatePercentageChange($currentRevenueSum, $priorRevenueSum);

        // 2. PIE/DOUGHNUT CHARTS PERCENTAGES
        // Total Order ring = percentage of delivered vs total orders
        $totalOrdersCountAll = Order::count();
        $this->deliveredPercent = $totalOrdersCountAll > 0 ? (int) round(($this->totalDelivered / $totalOrdersCountAll) * 100) : 0;

        // Total Revenue ring = today's revenue vs target (average of past 30 days daily revenue)
        $todayRevenue = (float) Order::where('status', 'delivered')->whereDate('created_at', today())->sum('total');
        $prior30DaysRevenueSum = (float) Order::where('status', 'delivered')
            ->whereBetween('created_at', [now()->subDays($this->periodDays)->startOfDay(), now()->subSeconds(1)])
            ->sum('total');
        $dailyAverageRevenue = $prior30DaysRevenueSum / $this->periodDays;
        $targetRevenue = $dailyAverageRevenue > 0 ? $dailyAverageRevenue : 5000; // default target to 5000 if average is 0
        $this->revenuePercent = $targetRevenue > 0 ? (int) round(($todayRevenue / $targetRevenue) * 100) : 0;

        // Customer Growth ring = unique customer count current 30 days vs prior 30 days
        $currentCustomersCount = Order::where('customer_phone', '!=', 'N/A')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->distinct('customer_phone')
            ->count('customer_phone');
        $priorCustomersCount = Order::where('customer_phone', '!=', 'N/A')
            ->whereBetween('created_at', [$priorStart, $priorEnd])
            ->distinct('customer_phone')
            ->count('customer_phone');
        $this->customerGrowthPercent = $this->calculatePercentageChange($currentCustomersCount, $priorCustomersCount);

        // 3. CHART ORDER (weekly line/area chart, Sun-Sat) mapped cleanly
        $weeklyOrders = Order::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->whereBetween('created_at', [now()->startOfWeek()->startOfDay(), now()->endOfWeek()->endOfDay()])
            ->groupBy('day')
            ->get();

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $orderChartData = [];
        $orderChartLabels = [];
        foreach ($days as $day) {
            $match = $weeklyOrders->first(fn ($item) => strtolower($item->day) === strtolower($day));
            $orderChartData[] = $match ? $match->count : 0;
            $orderChartLabels[] = $day;
        }
        $this->orderChartLabelsJson = json_encode($orderChartLabels);
        $this->orderChartDataJson = json_encode($orderChartData);

        // 4. TOTAL REVENUE CHART (monthly, current year vs previous year lines)
        $revenueCurrentYear = Order::selectRaw("MONTH(created_at) as month, SUM(total) as revenue")
            ->where('status', 'delivered')
            ->whereYear('created_at', $this->currentYear)
            ->groupBy('month')
            ->get();

        $revenueLastYear = Order::selectRaw("MONTH(created_at) as month, SUM(total) as revenue")
            ->where('status', 'delivered')
            ->whereYear('created_at', $this->lastYear)
            ->groupBy('month')
            ->get();

        $revenueCurrentYearData = [];
        $revenueLastYearData = [];
        for ($m = 1; $m <= 12; $m++) {
            $currMatch = $revenueCurrentYear->firstWhere('month', $m);
            $revenueCurrentYearData[] = $currMatch ? round($currMatch->revenue / 1000, 1) : 0;

            $lastMatch = $revenueLastYear->firstWhere('month', $m);
            $revenueLastYearData[] = $lastMatch ? round($lastMatch->revenue / 1000, 1) : 0;
        }
        $this->revenueCurrentYearJson = json_encode($revenueCurrentYearData);
        $this->revenueLastYearJson = json_encode($revenueLastYearData);

        // 5. CUSTOMER MAP (weekly bar chart, Current Week vs Last Week orders count per day)
        $lastWeekOrders = Order::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek()->startOfDay(), now()->subWeek()->endOfWeek()->endOfDay()])
            ->groupBy('day')
            ->get();

        $lastWeekData = [];
        foreach ($days as $day) {
            $match = $lastWeekOrders->first(fn ($item) => strtolower($item->day) === strtolower($day));
            $lastWeekData[] = $match ? $match->count : 0;
        }
        $this->currentWeekDataJson = json_encode($orderChartData); // Reuses Task 3 query
        $this->lastWeekDataJson = json_encode($lastWeekData);
    }

    private function calculatePercentageChange(float $current, float $previous): int
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (int) round((($current - $previous) / $previous) * 100);
    }
}
