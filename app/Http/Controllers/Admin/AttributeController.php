<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use App\Http\Requests\Attribute\UpdateAttributeRequest;
use App\Models\Attribute;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $department = Department::find($id);
        if(!$department)
        {
            return redirect()->back()->with("error" , "Department not found , please try again.");
        }

        return view("Admin.Attribute.create" , compact("department"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        DB::beginTransaction();
        try
        {
            foreach($data["attributes"] as $attribute)
            {
                $attr = Attribute::create([
                    'department_id' => $data['department_id'],
                    'name' => $attribute['name'],
                ]);

                if(!$attr)
                {
                    throw new \Exception("Failed to create attribute.");
                }

                foreach ($attribute['multi_text_values'] as $value)
                {
                    $attr->values()->create([
                        'value' => $value,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('department.show', $data["department_id"])->with("success" , "Attribute and values created successfully");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with("error" , "Failed to create attribute , please try again")->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $department = Department::with('attributes.values')->find($id);
        // if(!$department)
        // {
        //     return redirect()->back()->with("error" , "Department not found");
        // }

        // $attribute = Attribute::with("values")
        //     ->where("department_id" , $id)
        //     ->get();

        return view("Admin.Attribute.edit" , compact("department"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, string $id)
    {
        $data = $request->validated();
        // dd($data);
        $department = Department::with('attributes.values')->find($id);

        if (!$department)
        {
            return redirect()->back()->with("error", "Department not found");
        }

        if ($department->attributes->isEmpty())
        {
            return redirect()->back()->with("error", "This department has no attributes to update.");
        }

        foreach ($department->attributes as $attribute)
        {
            if ($attribute->values->isEmpty())
            {
                return redirect()->back()->with("error", "Attribute '{$attribute->name}' has no values.");
            }
        }

        DB::beginTransaction();
        try
        {
            foreach ($department->attributes as $attribute)
            {
                $attribute->values()->delete();
                $attribute->delete();
            }

            foreach($data["attributes"] as $attributeData)
            {
                $attr = Attribute::create([
                    'department_id' => $department->id,
                    'name' => $attributeData['name'],
                ]);

                if (!$attr) {
                    throw new \Exception("Failed to create attribute.");
                }

                foreach ($attributeData['multi_text_values'] as $value)
                {
                    $attr->values()->create([
                        'value' => $value,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('department.show', $department->id)->with("success", "Attributes updated successfully");
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with("error", "Failed to update attributes , please try again." . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department, Attribute $attribute)
    {
        if ($attribute->department_id !== $department->id)
        {
            return redirect()->back()->with("error" , "Don’t play this value");
        }

        $attribute->delete();

        return back()->with('success', 'Attribute deleted successfully.');
    }
}
