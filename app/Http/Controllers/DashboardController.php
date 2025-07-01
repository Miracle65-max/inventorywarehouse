<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard statistics
        $total_items = \App\Models\Item::count();
        $low_stock = \App\Models\Item::where('quantity', '<=', 10)->count();
        $total_suppliers = \App\Models\Supplier::count();
        $today_movements = \App\Models\StockMovement::whereDate('created_at', now()->toDateString())->count();

        // Recent stock movements (latest 10)
        $recent_movements = \App\Models\StockMovement::with(['item', 'user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Low stock items (latest 10)
        $low_stock_items = \App\Models\Item::where('quantity', '<=', 10)
            ->orderBy('quantity', 'asc')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'stats' => [
                'total_items' => $total_items,
                'low_stock' => $low_stock,
                'total_suppliers' => $total_suppliers,
                'today_movements' => $today_movements,
            ],
            'recent_movements' => $recent_movements,
            'low_stock_items' => $low_stock_items,
        ]);
    }
}
