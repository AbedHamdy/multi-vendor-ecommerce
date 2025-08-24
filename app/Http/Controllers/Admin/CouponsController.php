<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate();
        // dd($coupons);

        return view('Admin.Coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Admin.Coupon.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $coupon = Coupon::create($data);
        if(!$coupon)
        {
            return redirect()->back()->with("error" , "An error occurred while creating the coupon. Please try again.");
        }

        return redirect()->route("coupon.index")->with("success" , "Coupon created successfully.");
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
        $coupon = Coupon::find($id);
        if(!$coupon)
        {
            return redirect()->back()->with("error" , "Coupon not found or has been deleted.");
        }

        return view("Admin.Coupon.edit" , compact("coupon"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, string $id)
    {
        $data = $request->validated();
        // dd($data);
        $coupon = Coupon::find($id);
        if(!$coupon)
        {
            return redirect()->back()->with("error" , "Coupon not found or has been deleted.");
        }

        $coupon->update($data);
        return redirect()->route("coupon.index")->with("success" , "Coupon updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::find($id);
        if(!$coupon)
        {
            return redirect()->back()->with("error" , "Coupon not found or has been deleted.");
        }

        $coupon->delete();
        return redirect()->route("coupon.index")->with("success" , "Coupon deleted successfully.");
    }
}
