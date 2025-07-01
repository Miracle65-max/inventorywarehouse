<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $report_type = $request->input('type', 'inventory');
        $date_from = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
        $date_to = $request->input('date_to', Carbon::now()->toDateString());
        $report_data = [];
        $report_title = '';

        switch ($report_type) {
            case 'inventory':
                $report_title = 'Inventory Report';
                $report_data = DB::table('items as i')
                    ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.supplier_id')
                    ->select('i.*', 's.supplier_name', DB::raw('(i.quantity * i.cost_price) as total_value'))
                    ->orderBy('i.item_name')
                    ->get();
                break;
            case 'low_stock':
                $report_title = 'Low Stock Report';
                $report_data = DB::table('items as i')
                    ->leftJoin('suppliers as s', 'i.supplier_id', '=', 's.supplier_id')
                    ->select('i.*', 's.supplier_name')
                    ->where('i.quantity', '<=', 10)
                    ->orderBy('i.quantity', 'asc')
                    ->get();
                break;
            case 'stock_movements':
                $report_title = 'Stock Movements Report';
                $report_data = DB::table('stock_movements as sm')
                    ->join('items as i', 'sm.item_id', '=', 'i.id')
                    ->join('users as u', 'sm.user_id', '=', 'u.id')
                    ->select('sm.*', 'i.item_name', 'i.item_code', 'u.full_name as full_name')
                    ->whereBetween(DB::raw('DATE(sm.timestamp)'), [$date_from, $date_to])
                    ->orderBy('sm.timestamp', 'desc')
                    ->get();
                break;
            case 'sales':
                $report_title = 'Sales Report';
                $report_data = DB::table('sales_orders as so')
                    ->join('users as u', 'so.created_by', '=', 'u.id')
                    ->select('so.*', 'u.full_name as created_by_name')
                    ->whereBetween('so.order_date', [$date_from, $date_to])
                    ->orderBy('so.order_date', 'desc')
                    ->get();
                break;
            case 'suppliers':
                $report_title = 'Suppliers Report';
                $report_data = DB::table('suppliers as s')
                    ->leftJoin('items as i', 's.supplier_id', '=', 'i.supplier_id')
                    ->select('s.*', DB::raw('COUNT(i.item_id) as item_count'), DB::raw('SUM(i.quantity * i.cost_price) as total_value'))
                    ->groupBy(
                        's.supplier_id',
                        's.supplier_name',
                        's.contact_person',
                        's.contact_number',
                        's.email',
                        's.address',
                        's.status',
                        's.created_at',
                        's.updated_at',
                        's.deleted_at'
                    )
                    ->orderBy('s.supplier_name')
                    ->get();
                break;
        }

        return view('reports.index', compact('report_type', 'date_from', 'date_to', 'report_data', 'report_title'));
    }
}
