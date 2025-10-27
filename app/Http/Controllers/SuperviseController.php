<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Issue;
use App\Models\Media;
use App\Models\Supervise;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuperviseController extends Controller
{
    public function index()
    {


        if (Auth::user()->isAdmin())
            $Supervises = Supervise::latest()->paginate(10);
        else
        $Supervises = Supervise::where('user_id', Auth::id())->latest()->paginate(10);
        return view('supervises.index', compact('Supervises'));
    }

    public function create()
    {
        $employees=Employee::all();
        $selectedClient =Customer::all();
        $clients =  Customer::all();

        return view('supervises.create', compact('clients','selectedClient','employees'));
    }

    public function store(Request $request)
    {



        $request->validate([
            'customer_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'issue' => 'nullable|string',
            'is_completed' => 'boolean',
            'delay_reason' => 'nullable|string',
            'transferred_to_management' => 'boolean',
            'transfer_reason' => 'nullable|string',
            'solution_method' => 'nullable|string',
        ]);


        try {





            DB::beginTransaction();
          $supervise=  Supervise::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->input('customer_id'),
                'employee_id' => $request->input('employee_id'),
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'issue' => $request->input('issue'),
                'start_time' => now(),
                'is_completed' =>($request->input('is_completed'))?1:0,
                'delay_reason' => $request->input('delay_reason'),
                'transferred_to_management' => ($request->input('transferred_to_management'))?1:0,
                'transfer_reason' => $request->input('transfer_reason'),
                'solution_method' => $request->input('solution_method'),
                'location' => $request->input('location')
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/supervise', 'public');

                    Media::create([
                        'item_id' => $supervise->id,
                        'url'     => $path,
                        'type'    => 'Supervise',
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('supervises.index')->with('success', 'تمت الإضافة بنجاح');
        } catch (\Exception $e) {
            // Rollback if there's an error

            DB::rollBack();
            Log::error('Saving error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            // Return with old input and error message
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while saving. Please try again.']);
        }

    }


    public function edit($id)
    {
        $supervise = Supervise::with('media')->findOrFail($id);
        $employees = Employee::all();
        $selectedClient = Customer::all();
        $clients = Customer::all();

        return view('supervises.edit', compact('supervise', 'clients', 'selectedClient', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'issue' => 'nullable|string',
            'is_completed' => 'boolean',
            'delay_reason' => 'nullable|string',
            'transferred_to_management' => 'boolean',
            'transfer_reason' => 'nullable|string',
            'solution_method' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $supervise = Supervise::findOrFail($id);

            $supervise->update([
                'customer_id' => $request->input('customer_id'),
                'employee_id' => $request->input('employee_id'),
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'issue' => $request->input('issue'),
                'is_completed' => $request->input('is_completed') ? 1 : 0,
                'delay_reason' => $request->input('delay_reason'),
                'transferred_to_management' => $request->input('transferred_to_management') ? 1 : 0,
                'transfer_reason' => $request->input('transfer_reason'),
                'solution_method' => $request->input('solution_method'),
                'location' => $request->input('location'),
            ]);

            // رفع الصور الجديدة
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/supervise', 'public');

                    Media::create([
                        'item_id' => $supervise->id,
                        'url'     => $path,
                        'type'    => 'Supervise',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('supervises.index')->with('success', 'تم التحديث بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء التحديث. حاول مرة أخرى.']);
        }
    }

    public function show($id)
    {
        session(['old_super_url' => url()->previous()]);

        $supervise = Supervise::with('customer')->findOrFail($id);
        $client = Client::find($supervise->client_id);
        return view('supervises.show', compact('supervise','client'));
    }

    public function  destroy($id)
    {
        $supervise= Supervise::findOrFail($id);
        if ($supervise)
            $supervise->delete();

        return redirect()->route('supervises.index')->with('success', 'تم الحذف بنجاح');

    }
    public  function receive(  $issue)
    {

        $employees=Employee::all();
        $selectedClient =Customer::all();
        $clients =  Customer::all(); // جلب كل العملاء
        return view('supervises.create', compact('clients','selectedClient','employees','issue'));

    }
}
