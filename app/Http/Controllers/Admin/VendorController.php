<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::with("package" , "department")->paginate();
        return view("Admin.Vendor.index" , compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vendor = Vendor::with("package" , "department")->find($id);
        if(!$vendor)
        {
            return redirect()->back()->with("error" , "The selected vendor does not exist");
        }

        $products = $vendor->products()->paginate(10);
        return view("Admin.Vendor.show" , compact("vendor" , "products"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor)
        {
            return redirect()->back()->with("error", "The selected vendor does not exist.");
        }

        $newStatus = $vendor->status === 'active' ? 'inactive' : 'active';

        DB::beginTransaction();
        try
        {
            $vendor->status = $newStatus;
            $vendor->save();

            $productStatus = $newStatus === 'active' ? 1 : 0;
            $vendor->products()->update(['is_active' => $productStatus]);

            DB::commit();

            return redirect()->back()->with("success" ,"Vendor status changed to {$newStatus} successfully, and all related products have been updated.");
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with("error" ,"Something went wrong while updating the vendor status. Please try again later." . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::find($id);
        if(!$vendor)
        {
            return redirect()->back()->with("error" , "The selected vendor does not exist");
        }

        if($vendor->status == "active")
        {
            return redirect()->back()->with("error" , "Cannot delete an active vendor. Please deactivate the vendor first.");
        }

        DB::beginTransaction();
        try
        {
            $vendor->products()->delete();
            $vendor->coupons()->delete();

            $vendor->delete();

            DB::commit();
            return redirect()->back()->with("success", "Vendor and all related data have been deleted successfully.");
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with("error", "Something went wrong while deleting the vendor. Please try again." . $e->getMessage());
        }
    }
}
