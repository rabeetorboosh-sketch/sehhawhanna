<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Supervise;
use App\Models\User;
use Illuminate\Http\Request;

class SuperviseReportController extends Controller
{
    public function index(Request $request)
    {
        $customers=Customer::all();
        $users=User::all();
        $query = Supervise::with(['customer', 'user']);

        if ($request->filled('client_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('item.name', 'like', '%' . $request->client_name . '%');
            });
        }

        if ($request->filled('is_trans')) {
            if ($request->input('is_trans') == '1') {
                $query->where('transferred_to_management', 1);
            } elseif ($request->input('is_trans') == '0') {
                $query->where('transferred_to_management', 0);
            }
        }


        if ($request->filled('corrected')) {
            if ($request->input('corrected') == '1') {
                $query->where('is_completed', 1);
            } elseif ($request->input('corrected') == '0') {
                $query->where('is_completed', 0);
            }
        }


        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);

        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $selectedClient =Customer::all();
        $supervisors = $query->latest()->paginate(15)->appends($request->query());
        return view('reports.supervises.by_detail', compact('users','supervisors','selectedClient','customers'));
    }
    public function print(Request $request)
    {
        $customers=Customer::all();
        $users=User::all();
        $query = Supervise::with(['customer', 'user']);

        if ($request->filled('client_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('item.name', 'like', '%' . $request->client_name . '%');
            });
        }

        if ($request->filled('is_trans')) {
            if ($request->input('is_trans') == '1') {
                $query->where('transferred_to_management', 1);
            } elseif ($request->input('is_trans') == '0') {
                $query->where('transferred_to_management', 0);
            }
        }


        if ($request->filled('corrected')) {
            if ($request->input('corrected') == '1') {
                $query->where('is_completed', 1);
            } elseif ($request->input('corrected') == '0') {
                $query->where('is_completed', 0);
            }
        }


        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('start_time', '<=', $request->end_date);
        }

        $selectedClient =Customer::all();
        $supervisors = $query->latest()->paginate(15)->appends($request->query());
        return view('reports.supervises.printing.by_detail', compact('users','supervisors','selectedClient','customers'));
    }



    public function byUserDetail(Request $request)
    {
        $customers=Customer::all();
        $users = User::all();

        $query = Supervise::with(['customer.item', 'user']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if (request('summary')) {
            return $this->byUserSummary($query);
        }
        $supervises = $query->latest()->get()->groupBy('user_id');


$url='byUserDetail';
        $urlPrint='byUserDetailPrint';
        return view('reports.supervises.by_user_detail', compact('supervises', 'users','customers','url','urlPrint'));
    }
    public function byUserDetailPrint(Request $request)
    {
        $customers=Customer::all();
        $users = User::all();

        $query = Supervise::with(['customer.item', 'user']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if (request('summary')) {
            return $this->byUserSummaryPrint($query);
        }
        $supervises = $query->latest()->get()->groupBy('user_id');



        $urlPrint='byUserDetailPrint';


        return view('reports.supervises.printing.by_user_detail', compact('supervises', 'users','customers','urlPrint'));
    }

    public function byUserSummary( $query)
    {
        $customers=Customer::all();

        $stats = $query->selectRaw('
            user_id,
            COUNT(*) as total,
            SUM(CASE WHEN is_completed = 1 THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN transferred_to_management = 1 THEN 1 ELSE 0 END) as transferred
        ')
            ->groupBy('user_id')
            ->with('user')
            ->get();

        // نحسب النسبة في الـ collection
        foreach ($stats as $stat) {
            $stat->completion_rate = $stat->total > 0 ? round(($stat->completed / $stat->total) * 100, 2) : 0;
        }

        $users = User::all();
        $url='byUserDetail';
        $urlPrint='byUserDetailPrint';

        return view('reports.supervises.by_user_summary', compact('stats', 'users','customers','url','urlPrint'));
    }

    private function byUserSummaryPrint($query)
    {
        $stats = $query->selectRaw('
            user_id,
            COUNT(*) as total,
            SUM(CASE WHEN is_completed = 1 THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN transferred_to_management = 1 THEN 1 ELSE 0 END) as transferred
        ')
            ->groupBy('user_id')
            ->with('user')
            ->get();

        // نحسب النسبة في الـ collection
        foreach ($stats as $stat) {
            $stat->completion_rate = $stat->total > 0 ? round(($stat->completed / $stat->total) * 100, 2) : 0;
        }

        $users = User::all();
        $url='byUserDetail';
        $urlPrint='byUserDetailPrint';

        return view('reports.supervises.printing.by_user_summary', compact('stats', 'users','url','urlPrint'));

    }

}
