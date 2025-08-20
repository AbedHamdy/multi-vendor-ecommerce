<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class OrderVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vendor = Auth::guard("vendor")->user();

        $query = Order::whereHas('items.product', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->with(['items' => function ($q) use ($vendor) {
                $q->whereHas('product', function ($qq) use ($vendor) {
                    $qq->where('vendor_id', $vendor->id);
                })->with('product');
            }, 'user']);

        // Filter by status if provided
        if ($request->has('status') && $request->status)
        {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $orders = $query->latest()->paginate(15);

        return view("Vendor.Order.index", compact("orders"));
    }

    /**
     * Display the specified resource.
     */
    public function show($order_id , $product_id)
    {
        $vendor = Auth::guard("vendor")->user();

        $order = Order::find($order_id);
        if(!$order)
        {
            return redirect()->back()->with("error" , "Order not found , please try again.");
        }

        // dd($order);
        $item = OrderItem::where("product_id" , $product_id)
            ->where("order_id" , $order_id)
            ->with("product")
            ->first();

        if(!$item)
        {
            return redirect()->back()->with("error" , "Product not found , please try again.");
        }

        // dd($item);
        if ($item->product->vendor_id !== $vendor->id)
        {
            return redirect()->back()->with("error", "Unauthorized access to this product.");
        }

        return view("Vendor.Order.show", compact("order" , "item"));
    }

    /**
     * Confirm the order
     */
    public function confirm($order_id , $product_id)
    {
        $vendor = Auth::guard("vendor")->user();

        $order = Order::find($order_id);
        if(!$order)
        {
            return redirect()->back()->with("error" , "Order not found , please try again.");
        }

        // dd($order);
        $item = OrderItem::where("product_id" , $product_id)
            ->where("order_id" , $order_id)
            ->with("product")
            ->first();

        if(!$item)
        {
            return redirect()->back()->with("error" , "Product not found , please try again.");
        }

        if ($item->product->vendor_id !== $vendor->id)
        {
            return redirect()->back()->with("error", "Unauthorized access to this product.");
        }

        // dd($order);
        if ($order->status !== 'pending')
        {
            return redirect()->back()->with('error', 'Only pending orders can be confirmed.');
        }

        $order->update([
            'status' => 'shipped',
        ]);

        // Send notification to customer (optional)
        // $order->user->notify(new OrderConfirmed($order));

        return redirect()->back()->with('success', 'Order confirmed and shipped successfully!');
    }

    /**
     * Mark order as shipped
     */
    public function ship($order_id , $product_id)
    {
        $vendor = Auth::guard("vendor")->user();

        $order = Order::find($order_id);
        if(!$order)
        {
            return redirect()->back()->with("error" , "Order not found , please try again.");
        }

        // dd($order);
        $item = OrderItem::where("product_id" , $product_id)
            ->where("order_id" , $order_id)
            ->with("product")
            ->first();

        if(!$item)
        {
            return redirect()->back()->with("error" , "Product not found , please try again.");
        }

        if ($item->product->vendor_id !== $vendor->id)
        {
            return redirect()->back()->with("error", "Unauthorized access to this product.");
        }

        $order->update([
            'status' => 'shipped',
        ]);

        // Send notification to customer (optional)
        // $order->user->notify(new OrderShipped($order));

        return redirect()->back()->with('success', 'Order marked as shipped successfully!');
    }

    /**
     * Mark order as delivered
     */
    public function deliver($order_id , $product_id)
    {
        $vendor = Auth::guard("vendor")->user();

        $order = Order::find($order_id);
        if(!$order)
        {
            return redirect()->back()->with("error" , "Order not found , please try again.");
        }

        // dd($order);
        $item = OrderItem::where("product_id" , $product_id)
            ->where("order_id" , $order_id)
            ->with("product")
            ->first();

        if(!$item)
        {
            return redirect()->back()->with("error" , "Product not found , please try again.");
        }

        if ($item->product->vendor_id !== $vendor->id)
        {
            return redirect()->back()->with("error", "Unauthorized access to this product.");
        }

        $order->update([
            'status' => 'completed',
        ]);

        // Send notification to customer (optional)
        // $order->user->notify(new OrderDelivered($order));

        return redirect()->back()->with('success', 'Order completed and delivered successfully!');
    }

    /**
     * Cancel the order
     */
    public function cancel($order_id , $product_id)
    {
        $vendor = Auth::guard("vendor")->user();

        $order = Order::find($order_id);
        if(!$order)
        {
            return redirect()->back()->with("error" , "Order not found , please try again.");
        }

        // dd($order);
        $item = OrderItem::where("product_id" , $product_id)
            ->where("order_id" , $order_id)
            ->with("product")
            ->first();

        if(!$item)
        {
            return redirect()->back()->with("error" , "Product not found , please try again.");
        }

        if ($item->product->vendor_id !== $vendor->id)
        {
            return redirect()->back()->with("error", "Unauthorized access to this product.");
        }

        // dd("abed");

        $item->product->increment('stock', $item->quantity);
        // dd($item->product);
        $otherItemsCount = $order->items()
            ->where('order_id', $order_id)
            ->where('id', '!=', $item->id)
            ->count();

        // dd($otherItemsCount);
        if ($otherItemsCount > 0)
        {
            // dd("abed");
            $item->delete();
        }
        else
        {
            // dd("boda");
            $order->delete();
        }

        // Send notification to customer (optional)
        // $order->user->notify(new OrderCancelled($order));

        return redirect()->back()->with('success', 'Order cancelled successfully!');
    }
}
