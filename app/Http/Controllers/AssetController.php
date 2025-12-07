<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Item;
use App\Models\MainGroup;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceSolution;
use App\Models\SubGroup;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function index()
    {

        $assets = Asset::with(['item','branch'])->get();
        return view('admin.Asset.index', compact('assets'));
    }

    public function add()
    {
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 2)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 2);
        })->get();
        $branches   = Branch::all();
        return view('admin.Asset.add', compact('mainGroups','subGroups','branches'));
    }


    public function create(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'main_group'   => 'required|integer|exists:main_groups,id',
            'sub_group'    => 'required|integer|exists:sub_groups,id',
            'usage_date'   => 'nullable|date',
            'lifetime'     => 'nullable|integer',
            'description'  => 'nullable|string',
            'id_number'    => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {

            $item = Item::create([
                'name'          => $validated['name'],
                'type'          => 'Asset',
                'main_group_id' => $validated['main_group'],
                'sub_group_id'  => $validated['sub_group'],
                'department_id'  =>'2',
                'branch_id'     => 1,
            ]);

            Asset::create([
                'usage_date'   => $validated['usage_date'] ?? null,
                'item_id'   => $item->id,
                'lifetime'     => $validated['lifetime'] ?? null,
                'description'  => $validated['description'] ?? null,
                'id_number'    => $validated['id_number'] ?? null,
                'branch_id'    => 1,
            ]);
        });

        return redirect()->route('asset.add')->with('success', 'تمت الإضافة بنجاح ✅');
    }

    public  function show($id)
    {
        //بيانات الفرع الرئيسية
        $asset=Asset::findOrFail($id);

        //حركات الاصل
        $assetsMovements=AssetMovement::where('asset_number',$id)->get();

        //عمليات صيانة الاصل
        $assetMaintenance = MaintenanceSolution::whereHas('maintenanceRequest', function($query) use ($id) {
            $query->where('asset_id', $id);
        })->with('maintenanceRequest')->get();

        return view('admin.Asset.show', compact('asset','assetMaintenance','assetsMovements'));

    }

    public function edit( $id)
    {
        $asset=Asset::findOrFail($id);
        $mainGroups   = MainGroup::with('department')
            ->where('department_id', 2)
            ->get();

        $subGroups    = SubGroup::whereHas('mainGroup', function ($q){
            $q->where('department_id', 2);
        })->get();
        $subGroups  = SubGroup::all();
        $branches   = Branch::all();
        return view('admin.Asset.edit', compact('asset','mainGroups','subGroups','branches'));
    }

    public function update(Request $request, $id)
    {


        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'main_group'   => 'nullable|integer|exists:main_groups,id',
            'sub_group'    => 'nullable|integer|exists:sub_groups,id',
            'usage_date'   => 'nullable|date',
            'lifetime'     => 'nullable|integer',
            'description'  => 'nullable|string',
            'id_number'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated,$id) {
            $asset = Asset::findOrFail($id);
            $asset->item->update([
                'name' => $validated['name'],
                'main_group_id' => $validated['main_group'],
                'sub_group_id' => $validated['sub_group'],
            ]);
            $asset->update([

                'usage_date' => $validated['usage_date'],
                'lifetime' => $validated['lifetime'],
                'description' => $validated['description'],
                'id_number' => $validated['id_number'],
            ]);
        });
        return redirect()->route('asset.index')->with('success','تم التعديل بنجاح ✅');
    }


    public function delete($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();
        return redirect()->route('asset.index')->with('success','تم الحذف بنجاح ✅');
    }
}
