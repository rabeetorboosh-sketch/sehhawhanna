<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\IssueType;
use Illuminate\Http\Request;

class MainController extends Controller
{
   public  function tester()
   {
       $employees=Employee::all();
       $selectedClient =Customer::all();
       $clients =  Customer::all();

       $ths=['#','الاسم'];
       $add_url='#';
       $header='انواع المشاكل';
       $trs = IssueType::all(['id','name'])->toArray();
       return view('tester',compact('ths','add_url','header','trs','employees','selectedClient','clients'));
   }
}
