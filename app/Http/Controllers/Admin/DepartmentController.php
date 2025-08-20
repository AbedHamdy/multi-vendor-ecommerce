<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::withCount("vendors")->paginate();

        // dd($departments);
        return view("Admin.Department.index" , compact("departments"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Admin.Department.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $department = Department::create($data);
        if(!$department)
        {
            return redirect()->back()->with("error" , "Something error , please try again")->withInput();
        }

        return redirect()->route("department")->with("success" , "Department created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $department = Department::with("attributes.values")->withCount("vendors")->with("vendors")->find($id);
        if(!$department)
        {
            return redirect()->back()->with("error" , "Department not found");
        }
        // dd($department);

        return view("Admin.Department.show" , compact("department"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $department = Department::find($id);
        if(!$department)
        {
            return redirect()->back()->with("error" , "Department not found");
        }

        return view("Admin.Department.edit" , compact("department"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreDepartmentRequest $request, $id)
    {
        $data = $request->validated();
        // dd($data);

        $department = Department::find($id);
        if(!$department)
        {
            return redirect()->back()->with("error" , "Department not found");
        }

        // dd($department);
        $department->update($data);

        return redirect()->route("department")->with("success" , "Department updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        if(!$department)
        {
            return redirect()->back()->with("error" , "Department not found");
        }

        $department->delete();

        return redirect()->route("department")->with("success" , "Department deleted successfully");
    }
}
