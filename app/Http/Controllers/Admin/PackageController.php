<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Models\Feature;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::with('features')->paginate();

        return view("Admin.Package.index", compact("packages"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Admin.Package.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request)
    {
        $data = $request->validated();
        // dd($data["name"]);
        DB::beginTransaction();
        try
        {
            $package = Package::create([
                "name" => $data["name"],
                "price" => $data["price"],
            ]);

            if(!$package)
            {
                throw new \Exception("Error create package");
            }

            foreach($data["features"] as $feature)
            {
                $feature = Feature::create([
                    "package_id" => $package->id,
                    "title" => $feature,
                ]);

                if(!$feature)
                {
                    throw new \Exception("Error create feature");
                }
            }

            DB::commit();
            return redirect()->route("package")->with("success" , "Package and Features created successfully");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with("error" , "Error Create Package , please try again")->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $package = Package::with("features")->find($id);
        if(!$package)
        {
            return redirect()->back()->with("error" , "Package not found , please create package.");
        }

        return view("Admin.Package.show" , compact("package"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $package = Package::with("features")->find($id);
        if(!$package)
        {
            return redirect()->back()->with("error" , "Package not found , please create package.");
        }

        return view("Admin.Package.edit" , compact("package"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, $id)
    {
        $package = Package::find($id);
        if(!$package)
        {
            return redirect()->back()->with("error" , "Package not found , please create package.");
        }

        $data = $request->validated();
        // dd($data);
        DB::beginTransaction();
        try
        {
            $package->update([
                "name" => $data["name"],
                "price" => $data["price"],
            ]);

            $package->features()->delete();
            foreach ($data['features'] as $feature)
            {
                $package->features()->create(['title' => $feature]);
            }

            DB::commit();
            return redirect()->route('package.show' , $package->id)->with('success', 'Package updated successfully.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with("error" , "Error Update Package , please try again")->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $package = Package::find($id);
        if(!$package)
        {
            return redirect()->back()->with("error" , "Package not found , please create package.");
        }

        $package->features()->delete();
        $package->delete();

        return redirect()->route("package")->with("success" , "Package deleted successfully");
    }
}
