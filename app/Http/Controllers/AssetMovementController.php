<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetMovementController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin())
            $movements = AssetMovement::where('user_id',Auth::user()->id)->with('user','asset.item')->orderBy('movement_datetime', 'desc')->get();
        else
            $movements = AssetMovement::with('user','asset.item')->orderBy('movement_datetime', 'desc')->get();
        return view('asset_movements.index', compact('movements'));
    }

    public function create()
    {
        $assets = Asset::all();
        $employees = Employee::all();
        $customers = Customer::all();
        $suppliers = Supplier::all();
        return view('asset_movements.create', compact('assets','employees','suppliers','customers'));

    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'asset_id' => 'required|integer',
            'from_no' => 'nullable|integer',
            'from_no_type' => 'nullable|integer',
            'to_no' => 'nullable|integer',
            'to_no_type' => 'nullable|integer',
            'movement_datetime' => 'required|date',
            'reason' => 'nullable|string',
            'asset_status' => 'nullable',
            'movement_destination' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            AssetMovement::create([
                'user_id' => auth()->id(),
                'asset_number' => $validated['asset_id'],
                'from_item' => $validated['from_no'],
                'from_item_type' => $validated['from_no_type'],
                'to_item' => $validated['to_no'],
                'to_item_type' => $validated['to_no_type'],
                'movement_datetime' => $validated['movement_datetime'],
                'reason' => $validated['reason'] ?? null,
                'asset_status' => $validated['asset_status'] ?? null,
                'movement_destination' => $validated['movement_destination'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('asset_movements.index')
                ->with('success', 'تم إضافة حركة الأصل بنجاح');
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage());
        }   }

    public function show($id)
    {
        $movement = AssetMovement::with('user')->findOrFail($id);
        return view('asset_movements.show', compact('movement'));
    }

    public function edit($id)
    {
        $movement = AssetMovement::findOrFail($id);
        return view('asset_movements.edit', compact('movement'));
    }

    public function update(Request $request, $id)
    {
        $movement = AssetMovement::findOrFail($id);

        $validated = $request->validate([
            'asset_number' => 'required|integer',
            'from_item' => 'required|integer',
            'from_item_type' => 'required|integer',
            'to_item' => 'required|integer',
            'to_item_type' => 'required|integer',
            'movement_datetime' => 'required|date',
            'reason' => 'nullable|string',
            'asset_status' => 'required|integer',
            'movement_destination' => 'nullable|integer',
        ]);

        $movement->update($validated);

        return redirect()->route('asset_movements.index')->with('success', 'تم تعديل حركة الأصل بنجاح');
    }

    public function destroy($id)
    {
        $movement = AssetMovement::findOrFail($id);
        $movement->delete();

        return redirect()->route('asset_movements.index')->with('success', 'تم حذف حركة الأصل بنجاح');
    }
}
