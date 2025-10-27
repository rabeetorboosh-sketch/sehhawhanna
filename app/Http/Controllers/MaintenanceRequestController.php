<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\ReportItem;
use App\Models\User;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Report;
use App\Models\IssueType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin())
           $requests = MaintenanceRequest::with(['user', 'asset', 'employee', 'report.controlUnit', 'issueType'])->get();
        else
            $requests = MaintenanceRequest::where('user_id',Auth::user()->id)->with(['user', 'asset', 'employee', 'report.controlUnit', 'issueType'])->get();

        return view('maintenance_requests.index', compact('requests'));
    }

    public function create()
    {
        $users = User::all();
        $assets = Asset::all();
        $employees = Employee::all();
        $reports = ReportItem::with('controlUnit')->get();
        $issueTypes = IssueType::all();

        return view('maintenance_requests.create', compact('users', 'assets', 'employees', 'reports', 'issueTypes'));
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'employee_id' => 'nullable|exists:employees,id',
            'report_id' => 'nullable',
            'issue_text' => 'required|string',
            'issue_type_id' => 'nullable|exists:issue_types,id',
        ]);
        $validated['user_id']=Auth::user()->id;
        MaintenanceRequest::create($validated);

        return redirect()->route('maintenance_requests.index')->with('success', 'تمت إضافة الطلب بنجاح');
    }

    public function edit(MaintenanceRequest $maintenanceRequest)
    {
        $users = User::all();
        $assets = Asset::all();
        $employees = Employee::all();
        $reports = ReportItem::all();
        $issueTypes = IssueType::all();

        return view('maintenance_requests.edit', compact('maintenanceRequest', 'users', 'assets', 'employees', 'reports', 'issueTypes'));
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {

        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'employee_id' => 'nullable|exists:employees,id',
            'report_id' => 'nullable',
            'issue_text' => 'required|string',
            'issue_type_id' => 'nullable|exists:issue_types,id',
        ]);

        $maintenanceRequest->update($validated);

        return redirect()->route('maintenance_requests.index')->with('success', 'تم تعديل الطلب بنجاح');
    }
    public function show(MaintenanceRequest $maintenanceRequest)
    {
        // نجيب العلاقات المرتبطة لعرضها مباشرة في الـ view
        $maintenanceRequest->load(['employee', 'issueType', 'report', 'asset', 'user']);

        return view('maintenance_requests.show', compact('maintenanceRequest'));
    }
    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->delete();
        return redirect()->route('maintenance_requests.index')->with('success', 'تم حذف الطلب بنجاح');
    }

    public  function approve($id)
    {
        $request = MaintenanceRequest::findOrFail($id);
        $request->status=  $request->status==1?0:1;
        $request->save();
        return redirect()->route('maintenance_requests.index')->with('success', 'تم حذف الطلب بنجاح');
    }

}
