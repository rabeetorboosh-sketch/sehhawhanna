<?php

// app/Http/Controllers/MaintenanceSolutionController.php

namespace App\Http\Controllers;

use App\Models\MaintenanceSolution;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceSolutionController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin())
        $solutions = MaintenanceSolution::with('maintenanceRequest')->get();
        else
        $solutions = MaintenanceSolution::where('user_id',Auth::user()->id)->with('maintenanceRequest')->get();


        return view('maintenance_solutions.index', compact('solutions'));
    }

    public function create($request="")
    {

        $requests = MaintenanceRequest::where('status',1)->get();
        return view('maintenance_solutions.create', compact('requests','request'));
    }

    public function store(Request $request)
    {


        $data = $request->validate([
            'maintenance_request_id' => 'required|exists:maintenance_requests,id',
            'issue_reason' => 'nullable|string',
            'solution_text' => 'nullable|string',
            'time_spent' => 'nullable|numeric',
            'bad_parts' => 'nullable|string',
            'workshop_name' => 'nullable|string',
            'maintenance_responsible' => 'nullable|string',
            'repair_cost' => 'nullable|numeric',
            'temporary_solution' => 'nullable|boolean',
            'has_warranty' => 'nullable|boolean',
            'warranty_type' => 'nullable|string',
            'warranty_expiry' => 'nullable|date',
            'delivered' => 'nullable|boolean',
        ]);

        // تحويل checkboxes
        $data['temporary_solution'] = $request->has('temporary_solution');
        $data['delivered'] = $request->has('delivered');
        $data['user_id']=Auth::user()->id;
        if(!$request->has('has_warranty')){
            $data['warranty_type'] = "";
            $data['warranty_expiry'] = "";

        }

        MaintenanceSolution::create($data);

        return redirect()->route('maintenance_solutions.index')->with('success', 'تم حفظ الحل بنجاح');
    }

    public function edit(MaintenanceSolution $maintenanceSolution)
    {

        $requests = MaintenanceRequest::where('status',1)->all();

        return view('maintenance_solutions.edit', [
            'solution' => $maintenanceSolution,
            'requests' => $requests
        ]);  }

    public function update(Request $request, MaintenanceSolution $maintenanceSolution)
    {
        $data = $request->validate([
            'maintenance_request_id' => 'required|exists:maintenance_requests,id',
            'issue_reason' => 'required|string',
            'solution_text' => 'nullable|string',
            'time_spent' => 'nullable|numeric',
            'bad_parts' => 'nullable|string',
            'workshop_name' => 'nullable|string',
            'maintenance_responsible' => 'nullable|string',
            'repair_cost' => 'nullable|numeric',
            'temporary_solution' => 'nullable|boolean',
            'has_warranty' => 'nullable|boolean',
            'warranty_type' => 'nullable|string',
            'warranty_expiry' => 'nullable|date',
            'delivered' => 'nullable|boolean',
        ]);

        // تحويل checkboxes
        $data['temporary_solution'] = $request->has('temporary_solution');
        $data['has_warranty'] = $request->has('has_warranty');
        $data['delivered'] = $request->has('delivered');

        $maintenanceSolution->update($data);

        return redirect()->route('maintenance_solutions.index')->with('success', 'تم تحديث الحل بنجاح');
    }

    public function show($id)
    {

        $solution =MaintenanceSolution::find($id);

        return view('maintenance_solutions.show', compact('solution'));
    }

    public function destroy(MaintenanceSolution $maintenanceSolution)
    {
        $maintenanceSolution->delete();
        return redirect()->route('maintenance_solutions.index')->with('success', 'تم حذف الحل بنجاح');
    }
}
