<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AssetsMovementsReportsController extends Controller
{
    public function byOperation($id = null)
    {
        $users = User::all();
        $employees = Employee::all();
        $assets = Asset::all();

        $query = AssetMovement::with(['user', 'asset.item']);

        // لو المستخدم أدمن يشوف كل شيء، غير كذا يشوف سجلاته فقط
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        // فلترة حسب المستخدم
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // فلترة حسب الموظف (كمصدر أو وجهة)
        if (request('employee_id')) {
            $employeeId = request('employee_id');
            $query->where(function ($q) use ($employeeId) {
                $q->where(function ($sub) use ($employeeId) {
                    $sub->where('from_item', $employeeId)
                        ->where('from_item_type', '4');
                })
                    ->orWhere(function ($sub) use ($employeeId) {
                        $sub->where('to_item', $employeeId)
                            ->where('to_item_type', '4');
                    });
            });
        }

        // فلترة حسب الأصل
        if (request('asset_id')) {
            $query->where('asset_number', request('asset_id'));
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
        if (!request('from_date') && !request('to_date')) {
            $query->whereDate('created_at', now()->toDateString());
        }
        // تنفيذ الاستعلام
        $movements = $query->orderBy('movement_datetime', 'desc')->get();

        $urlPrint = 'byOperationPrint';
        $url = 'byOperation';
        $title = 'حسب العملية';

        return view('reports.assetsMovements.byOperation', compact(
            'movements',
            'urlPrint',
            'users',
            'employees',
            'assets',
            'url',
            'id',
            'title'
        ));
    }

    public  function byOperationPrint()
  {
      $query = AssetMovement::with(['user', 'asset.item']);

      // لو المستخدم أدمن يشوف كل شيء، غير كذا يشوف سجلاته فقط
      if (!Auth::user()->isAdmin()) {
          $query->where('user_id', Auth::id());
      }

      // فلترة حسب المستخدم
      if (request('user_id')) {
          $query->where('user_id', request('user_id'));
      }

      // فلترة حسب الموظف (كمصدر أو وجهة)
      if (request('employee_id')) {
          $employeeId = request('employee_id');
          $query->where(function ($q) use ($employeeId) {
              $q->where(function ($sub) use ($employeeId) {
                  $sub->where('from_item', $employeeId)
                      ->where('from_item_type', '4');
              })
                  ->orWhere(function ($sub) use ($employeeId) {
                      $sub->where('to_item', $employeeId)
                          ->where('to_item_type', '4');
                  });
          });
      }

      // فلترة حسب الأصل
      if (request('asset_id')) {
          $query->where('asset_number', request('asset_id'));
      }



      if (request('from_date') && request('to_date')) {
          $query->whereBetween('created_at', [
              request('from_date'),
              request('to_date') . ' 23:59:59'
          ]);
      } elseif (request('from_date')) {
          $query->whereDate('created_at', request('from_date'));
      }
      if (!request('from_date') && !request('to_date')) {
          $query->whereDate('created_at', now()->toDateString());
      }
      // تنفيذ الاستعلام
      $movements = $query->orderBy('movement_datetime', 'desc')->get();

      return view('reports.assetsMovements.print.byOperation', compact('movements'));

  }

}
