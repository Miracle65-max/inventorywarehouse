<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditTrail::with('user');

        // Optional filters
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->module) {
            $query->where('module', $request->module);
        }
        if ($request->action) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        $auditTrails = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('audit_trails.index', [
            'auditTrails' => $auditTrails,
            'users' => User::orderBy('full_name')->get(['id', 'full_name', 'username']),
            'modules' => AuditTrail::query()
                ->selectRaw('DISTINCT IFNULL(module, "General") as module')
                ->whereNotNull('module')
                ->orderBy('module')
                ->pluck('module'),
            'module_list' => AuditTrail::query()
                ->selectRaw('DISTINCT IFNULL(module, "General") as module')
                ->whereNotNull('module')
                ->orderBy('module')
                ->pluck('module'),
            'user_filter' => $request->user_id,
            'module_filter' => $request->module,
            'action_filter' => $request->action,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ]);
    }
}
