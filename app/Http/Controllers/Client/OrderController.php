<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
        ]);

        // dd($data);
        if($data["payment_method"] == "cash")
        {
            DB::beginTransaction();
            try
            {
                $products = Cart::where("user_id" , $user->id)->get();
                // dd($products);
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
                $tax = $subtotal * ($taxPercent / 100);
                $finalTotal = $subtotal + $tax + $deliveryFee;

                $order->update([
                    "price" => $finalTotal,
                ]);

                Cart::where('user_id', $user->id)->delete();
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
                $tax = $subtotal * ($taxPercent / 100);
                $finalTotal = $subtotal + $tax + $deliveryFee;

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Delivery Fee'],
                        'unit_amount' => intval($deliveryFee * 100),
                    ],
                    'quantity' => 1,
                ];

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Tax (10%)'],
                        'unit_amount' => intval($tax * 100),
                    ],
                    'quantity' => 1,
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
            $tax = $subtotal * ($taxPercent / 100);
            $finalTotal = $subtotal + $tax + $deliveryFee;

            $order->update([
                "total_price" => $finalTotal,
                "status" => 'paid',
            ]);

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
