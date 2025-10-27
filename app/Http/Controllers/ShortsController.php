<?php

namespace App\Http\Controllers;

use App\Models\Short;
use Illuminate\Http\Request;

class ShortsController extends Controller
{
    public function store(Request $request)
    {

        $shorts=Short::where('name',$request->name)->where('url',$request->url)->first();
       if (!$shorts){
         Short::create([
            'name'=> $request->name,
            'url'=> $request->url,
         ]);
       }
        return redirect()->back();


    }
    public function delete(Request $request)
    {
        $shorts=Short::where('name',$request->name)->where('url',$request->url)->first();

        if ($shorts){
     $shorts->delete();
       }
        return redirect()->back();


    }
}
