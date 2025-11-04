<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Rating;
use App\Models\RatingItem;
use App\Models\RatingUnit;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    /**
     * عرض جميع التقييمات
     */
    public function index($id=null)
    {

        if($id) {
            $ratings = Rating::with(['user', 'items.ratingUnit'])->where('item_id',$id)->get();
        }else{
            $ratings = Rating::with(['user', 'items.ratingUnit'])->get();

        }
        return view('ratings.index', compact('ratings'));
    }
    public function show($id)
    {
        $back_url= url()->previous() ;

        $rating = Rating::with(['user', 'items.ratingUnit'])->findOrFail($id);
        return view('ratings.show', compact('rating','back_url'));
    }
    /**
     * عرض صفحة إضافة تقييم جديد
     */
    public function create()
    {
        $users = User::all();
        $employees = Employee::all();
        $units = RatingUnit::all();

        return view('ratings.create', compact('users', 'employees', 'units'));
    }

    /**
     * حفظ التقييم الجديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'date' => 'required|date',
            'rating_unit_id' => 'required|array',
            'percentage' => 'required|array',
        ]);
        DB::beginTransaction();

        try {
            $rating = Rating::create([
                'user_id' => Auth::id(),
                'item_id' => $request->item_id,
                'date' => $request->date,
            ]);

            foreach ($request->rating_unit_id as $i => $unitId) {
                if (!empty($request->percentage[$i])) {
                    RatingItem::create([
                        'rating_id' => $rating->id,
                        'rating_unit_id' => $unitId,
                        'percentage' => $request->percentage[$i],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('ratings.index')->with('success', 'تمت إضافة التقييم بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }

    }

    /**
     * عرض صفحة تعديل تقييم
     */
    public function edit($id)
    {
        $rating = Rating::with('items')->findOrFail($id);
        $users = User::all();
        $employees = Employee::all();
        $units = RatingUnit::all();

        return view('ratings.edit', compact('rating', 'users', 'employees', 'units'));
    }

    /**
     * تحديث التقييم
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'date' => 'required|date',
            'rating_unit_id' => 'required|array',
            'percentage' => 'required|array',
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update($request->only('item_id', 'date'));

        RatingItem::where('rating_id', $rating->id)->delete();

        foreach ($request->rating_unit_id as $i => $unitId) {

            if ($request->percentage[$i]!=null){
                RatingItem::create([
                    'rating_id' => $rating->id,
                    'rating_unit_id' => $unitId,
                    'percentage' => $request->percentage[$i],
                ]);
            }

        }

        return redirect()->route('ratings.index')->with('success', 'تم تحديث التقييم بنجاح');
    }

    /**
     * حذف التقييم
     */
    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->items()->delete();
        $rating->delete();

        return redirect()->route('ratings.index')->with('success', 'تم حذف التقييم بنجاح');
    }
}
