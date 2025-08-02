<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendor = auth('vendor')->user();
        $coupons = Coupon::where('vendor_id', $vendor->id)->paginate();

        return view("Vendor.Coupon.index" , compact("coupons"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        do
        {
            $randomCode = strtoupper(Str::random(8));
            $code = $randomCode . now()->format('YmdHis');
        }
        while (Coupon::where('code', $code)->exists());

        return view("Vendor.Coupon.create", compact('code'));
    }

    /**
     * Store a newly created resource in storage.
     */
        public function store(StoreCouponRequest $request)
        {
            $data = $request->validated();
            // dd($data);
            $data['vendor_id'] = auth('vendor')->id();

            Coupon::create($data);
            return redirect()->route('coupon')->with('success', 'Coupon created successfully.');
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
        $coupon = Coupon::where('id', $id)
            ->where('vendor_id', auth("vendor")->user()->id)
            ->first();

        if(!$coupon)
        {
            return redirect()->back()->with("error", "Coupon not found");
        }

        return view("Vendor.Coupon.edit", compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, $id)
    {
        $data = $request->validated();
        // dd($data);
        $coupon = Coupon::where('id', $id)
            ->where('vendor_id', auth('vendor')->id())
            ->first();

        if(!$coupon)
        {
            return redirect()->back()->with("error", "Coupon not found");
        }

        $data['vendor_id'] = auth('vendor')->id();

        $coupon->update($data);
        return redirect()->route('coupon')->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::where('id', $id)
            ->where('vendor_id', auth('vendor')->id())
            ->first();
            
        if(!$coupon)
        {
            return redirect()->back()->with("error", "Coupon not found");
        }

        $coupon->delete();
        return redirect()->route('coupon')->with('success', 'Coupon deleted successfully.');
    }
}
