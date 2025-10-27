<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Item;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class RatingsReportsController extends Controller
{
    public function byOperationDetail($id =null)
    {

        $users = User::all();
        $items = Item::where('type','Employee')->get();


        $query = Rating::with([
            'user',
            'items',
            'item',

        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        if (request('item_id')) {
            $query->where('item_id', request('item_id'));
        }

        // فلترة حسب التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $ratings = $query->get();
        if (request('summary')) {
            return $this->byOperationSummary($ratings);
        }
        $urlPrint='byOperationDetailPrint';
        $title= 'التقييمات حسب العملية - تحليلي';

        return view('reports.employeesRating.by_operation_detail', compact(
            'ratings',
            'users',
            'urlPrint',
            'items',
            'title',
            'id',
        ));
    }
    public function byOperationDetailPrint($id =null)
    {

        $users = User::all();
        $items = Item::where('type','Employee')->get();


        $query = Rating::with([
            'user',
            'items',
            'item',

        ]);

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        if (request('item_id')) {
            $query->where('item_id', request('item_id'));
        }

        // فلترة حسب التاريخ
        if (request('from_date') && request('to_date')) {
            $query->whereBetween('created_at', [
                request('from_date'),
                request('to_date') . ' 23:59:59'
            ]);
        } elseif (request('from_date')) {
            $query->whereDate('created_at', request('from_date'));
        }

        $ratings = $query->get();
        if (request('summary')) {
            return $this->byOperationSummaryPrint($ratings);
        }
        $urlPrint='byOperationDetailPrint';
        $title= 'التقييمات حسب العملية - تحليلي';

        return view('reports.employeesRating.print.by_operation_detail', compact(
            'ratings',
            'users',
            'urlPrint',
            'items',
            'title',
            'id',
        ));
    }


    public function byOperationSummary($ratings)
    {
        $users = User::all();
        $items = Item::where('type', 'Employee')->get();

        $urlPrint = 'byOperationDetailPrint';
        $title = 'التقييمات حسب الموظف - إجمالي';

        return view('reports.employeesRating.by_operation_summary', compact(
            'ratings',
            'users',
            'urlPrint',
            'items',
            'title',

        ));
    }

    public function byOperationSummaryPrint($ratings)
    {

        $users = User::all();
        $items = Item::where('type','Employee')->get();



        $urlPrint='byOperationDetailPrint';
        $title= 'التقييمات حسب العملية - تحليلي';

        return view('reports.employeesRating.print.by_operation_summary', compact(
            'ratings',
            'users',
            'urlPrint',
            'items',
            'title',

        ));
    }


}
