<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()->paginate();
        // dd($orders);
        return view("Admin.Order.index" , compact("orders"));
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
        $order = Order::with(['items.product.images'])->find($id);
        if(!$order)
        {
            return redirect()->back()->with("error" , "Order not found , please try again.");
        }

        return view("Admin.Order.show" , compact("order"));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);

        if ($request->status === 'cancelled')
        {
            foreach ($order->items as $item)
            {
                $item->product->update([
                    'stock' => $item->product->stock + $item->quantity,
                ]);
            }
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
