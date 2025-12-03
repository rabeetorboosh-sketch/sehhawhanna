<?php

namespace App\Http\Controllers;

use App\Models\Short;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShortsController extends Controller
{
    public function store(Request $request)
    {

        $shorts=Short::where('name',$request->name)->where('url',$request->url)->where('user_id',Auth::id())->first();
       if (!$shorts){
         Short::create([
            'user_id'=> Auth::id(),
            'name'=> $request->name,
            'url'=> $request->url,
         ]);
       }
        return redirect()->back();


    }
    public function delete(Request $request)
    {
        $shorts=Short::where('name',$request->name)->where('url',$request->url)->where('user_id',Auth::id())->first();
        if ($shorts){
           $shorts->delete();
        }
        return redirect()->back();
    }
}
