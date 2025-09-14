<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Notifications\AdminNewOrderNotification;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
// use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;


class OrderController extends Controller
{
    public function processCheckout(Request $request)
    {
        $user = Auth::guard("web")->user();

        $data = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:stripe,cash',
            'price' => 'required|numeric|min:0',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        if (!empty($data['coupon_code']))
        {
            $coupon = Coupon::where('code', $data['coupon_code'])->first();
            if(!$coupon)
            {
                return redirect()->back()->with('error' , 'Coupon not found , please try again.')->withInput();
            }

            $usedBefore = CouponUser::where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->where("used" , true)
                ->first();

            if ($usedBefore)
            {
                return redirect()->back()->with('error' , 'You have already used this coupon before.')->withInput();
            }

            if($coupon->used_times >= $coupon->usage_limit)
            {
                return redirect()->back()->with('error' , 'This coupon has reached its usage limit.')->withInput();
            }
        }

        // dd($data);

        if($data["payment_method"] == "cash")
        {
            DB::beginTransaction();
            try
            {
                $products = Cart::where("user_id" , $user->id)->get();
                // dd($products);
                // dd($data);

                $order = Order::create([
                    "user_id" => $user->id,
                    "payment_method" => "cod",
                    "shipping_address" => $data["shipping_address"],
                    "phone" => $data["phone"],
                    "total_price" => 0,
                ]);

                if(!$order)
                {
                    throw new \Exception("Field to save order , please try again.");
                }

                $totalPrice = 0;

                foreach ($products as $cartItem)
                {
                    $product = Product::where('id', $cartItem->product_id)
                        ->lockForUpdate()
                        ->first();

                    if ($product->stock < $cartItem->quantity)
                    {
                        throw new \Exception("Product '{$product->name}' does not have enough stock.");
                    }

                    $priceBeforeDiscount = $product->price * $cartItem->quantity;
                    $discount = $product->discount ?? 0;
                    $finalPrice = $priceBeforeDiscount * (1 - $discount / 100);
                    $totalPrice += $finalPrice;

                    if (!empty($data['coupon_code']))
                    {
                        if($coupon->discount_type == "fixed")
                        {
                            $totalPrice -= $coupon->value;
                        }
                        else if($coupon->discount_type == "percent")
                        {
                            $totalPrice -= ($totalPrice * ($coupon->value / 100));
                        }
                    }

                    $item = OrderItem::create([
                        "order_id" => $order->id,
                        "product_id" => $product->id,
                        "quantity" => $cartItem->quantity,
                        "price" => $finalPrice,
                    ]);

                    if(!$item)
                    {
                        throw new \Exception("Field save order , please try again.");
                    }

                    $product->update([
                        "quantity" => $product->quantity - $cartItem->quantity,
                    ]);

                    // dd($item->specifications);
                    $vendor = $item->product->vendor;
                    Notification::send($vendor, new NewOrderNotification($order, $item));
                }

                $deliveryFee = 10;
                $taxPercent = 10;
                $subtotal = $totalPrice;
                // dd($totalPrice);

                if (!empty($data['coupon_code']))
                {
                    // dd($data);
                    if($coupon->discount_type == "fixed")
                    {
                        $subtotal -= $coupon->value;
                    }
                    else if($coupon->discount_type == "percent")
                    {
                        $subtotal -= ($subtotal * ($coupon->value / 100));
                    }

                    // Mark coupon as used by the user
                    CouponUser::create([
                        'user_id' => $user->id,
                        'coupon_id' => $coupon->id,
                    ]);

                    $coupon->update([
                        "used_times" => $coupon->used_times + 1,
                    ]);
                }

                $tax = $subtotal * ($taxPercent / 100);
                $finalTotal = $subtotal + $tax + $deliveryFee;
                // dd($finalTotal);
                $order->update([
                    "total_price" => $finalTotal,
                ]);

                // dd($order);
                $admins = Admin::all();
                Notification::send($admins, new AdminNewOrderNotification($order));

                Cart::where('user_id', $user->id)->delete();
                $usedBefore->update([
                    "used" => true,
                ]);

                DB::commit();
                return redirect()->route("view_product")->with("success" , "Order placed successfully.");
            }
            catch(\Exception $e)
            {
                DB::rollBack();
                return redirect()->back()->with("error" , "field submit payment , please try again.");
            }
        }
        else if($data["payment_method"] == "stripe")
        {
            try
            {
                $cartItems = Cart::where("user_id", $user->id)->get();
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                $lineItems = [];
                $subtotal = 0;

                foreach($cartItems as $cartItem)
                {
                    $product = $cartItem->product;
                    $discount = $product->discount ?? 0;
                    $finalPrice = $product->price * $cartItem->quantity * (1 - $discount / 100);

                    $subtotal += $finalPrice;

                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => ['name' => $product->name],
                            'unit_amount' => intval($finalPrice * 100),
                        ],
                        'quantity' => 1,
                    ];
                }

                $deliveryFee = 10;
                $taxPercent = 10;
                if (!empty($data['coupon_code']))
                {
                    // dd($data);
                    if($coupon->discount_type == "fixed")
                    {
                        $subtotal -= $coupon->value;
                    }
                    else if($coupon->discount_type == "percent")
                    {
                        $subtotal -= ($subtotal * ($coupon->value / 100));
                    }

                    // Mark coupon as used by the user
                    // CouponUser::create([
                    //     'user_id' => $user->id,
                    //     'coupon_id' => $coupon->id,
                    // ]);

                    // $coupon->update([
                    //     "used_times" => $coupon->used_times + 1,
                    // ]);
                }

                $tax = $subtotal * ($taxPercent / 100);
                $finalTotal = $subtotal + $tax + $deliveryFee;
                // dd($finalTotal);

                $lineItems = [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Order Payment',
                            ],
                            'unit_amount' => intval($finalTotal * 100), // السعر النهائي بعد الخصم
                        ],
                        'quantity' => 1,
                    ],
                ];

                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => route('client.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('select_payment'),
                    'metadata' => [
                        'user_id' => $user->id,
                        'shipping_address' => $data['shipping_address'],
                        'phone' => $data['phone'],
                        'total_price' => $finalTotal,
                        'coupon_id' => $coupon->id ?? null,
                    ],
                ]);

                return redirect($session->url);
            }
            catch(\Exception $e)
            {
                return redirect()->route('select_payment')->with('error', 'There was a problem connecting to the payment gateway. Please try again.')->withInput();
            }
        }
        else
        {
            return redirect()->back()->with("error" , "Payment method not supported.")->withInput();
        }
    }

    public function checkoutSuccess(Request $request)
    {
        // dd($request->all());
        $user = Auth::guard('web')->user();

        // Get latest Stripe session
        $sessionId = $request->get('session_id');
        // dd($sessionId);
        if(!$sessionId) return redirect()->route('select_payment');
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $metadata = $session->metadata;
        // dd($metadata);

        $cartItems = Cart::where('user_id', $user->id)->get();
        if($cartItems->isEmpty()) return redirect()->route('select_payment');

        $couponId = $metadata->coupon_id ?? null;
        if ($couponId)
        {
            $coupon = Coupon::find($couponId);
        }

        DB::beginTransaction();
        try
        {
            $order = Order::create([
                "user_id" => $user->id,
                "payment_method" => 'stripe',
                "shipping_address" => $metadata->shipping_address,
                "phone" => $metadata->phone,
                "total_price" => 0,
                "status" => 'paid',
                "transaction_id" => $session->payment_intent,
            ]);

            $subtotal = 0;
            foreach($cartItems as $cartItem)
            {
                $product = Product::where('id', $cartItem->product_id)
                    ->lockForUpdate()
                    ->first();

                // dd($cartItem->quantity);
                if($product->stock < $cartItem->quantity)
                {
                    // dd($product->stock);
                    throw new \Exception("Product '{$product->name}' does not have enough stock.");
                }

                $priceBeforeDiscount = $product->price * $cartItem->quantity;
                $discount = $product->discount ?? 0;
                $finalPrice = $priceBeforeDiscount * (1 - $discount / 100);

                // if (!empty($data['coupon_code']))
                //     {
                //     if($coupon->discount_type == "fixed")
                //     {
                //         $finalPrice -= $coupon->value;
                //     }
                //     else if($coupon->discount_type == "percent")
                //     {
                //         $finalPrice -= ($finalPrice * ($coupon->value / 100));
                //     }
                // }

                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $product->id,
                    "quantity" => $cartItem->quantity,
                    "price" => $finalPrice,
                ]);

                $vendor = $cartItem->product->vendor;
                Notification::send($vendor, new NewOrderNotification($order, $cartItem));

                $product->update([
                    "quantity" => $product->quantity - $cartItem->quantity
                ]);

                $subtotal += $finalPrice;
            }

            $deliveryFee = 10;
            $taxPercent = 10;

            if (!empty($coupon))
            {
                if($coupon->discount_type == "fixed")
                {
                    $subtotal -= $coupon->value;
                }
                else if($coupon->discount_type == "percent")
                {
                    $subtotal -= ($subtotal * ($coupon->value / 100));
                }

                // Mark coupon as used by the user
                CouponUser::create([
                    'user_id' => $user->id,
                    'coupon_id' => $coupon->id,
                    "used" => true,
                ]);

                $coupon->update([
                    "used_times" => $coupon->used_times + 1,
                ]);
            }

            $tax = $subtotal * ($taxPercent / 100);
            $finalTotal = $subtotal + $tax + $deliveryFee;

            $order->update([
                "total_price" => $finalTotal,
                "status" => 'paid',
            ]);

            $admins = Admin::all();
            Notification::send($admins, new AdminNewOrderNotification($order));

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();
            return redirect()->route('view_product')->with('success', 'Order placed successfully.');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('select_payment')->with('error', $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard("web")->user();
        // dd($user);

        $orders = Cart::where("user_id" , $user->id)->get();
        if(!$orders)
        {
            return redirect()->back()->with("error" , "Your cart is empty. Please add some items before proceeding to checkout.");
        }

        $totalPrice = 0;

        foreach ($orders as $order)
        {
            $priceBeforeDiscount = $order->product->price * $order->quantity;
            $discount = $order->product->discount ?? 0;
            $finalPrice = $priceBeforeDiscount * (1 - $discount / 100);
            $totalPrice += $finalPrice;
        }

        return view("Client.views.checkout" , compact("totalPrice"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function myOrders()
    {
        // dd("abed");
        $user = Auth::guard("web")->user();
        // dd($user);

        // $orders = Order::where("user_id" , $user->id)->get();
        $orders = Order::where("user_id", $user->id)
            ->whereIn("status", ['pending', 'paid', 'shipped'])
            ->get();

        if(!$orders)
        {
            return redirect()->back()->with("error" , "Your cart is empty. Please add some items before proceeding to checkout.");
        }

        // dd($orders);
        return view("Client.views.MyOrders" , compact("orders"));
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
    public function show(string $id)
    {
        //
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
