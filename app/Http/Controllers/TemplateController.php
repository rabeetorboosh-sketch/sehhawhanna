<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Department;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::with('department')->get();
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('templates.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);

        Template::create($request->all());
        return redirect()->route('templates.index')->with('success', 'تمت إضافة القالب');
    }

    public function edit(Template $template)
    {
        $departments = Department::all();
        return view('templates.edit', compact('template', 'departments'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);

        $template->update($request->all());
        return redirect()->route('templates.index')->with('success', 'تم تحديث القالب');
    }

    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'تم حذف القالب');
    }
}
