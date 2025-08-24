<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\StoreCartRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function addToCart(StoreCartRequest $request)
    {
        if (!auth()->check())
        {
            return redirect()->route('login')->with('error', 'Please login to add products to cart.');
        }
        $data = $request->validated();
        // dd($data);

        DB::beginTransaction();
        try
        {
            $product = Product::where("id" , $data["product_id"])
                ->lockForUpdate()
                ->first();

            if(!$product)
            {
                throw new \Exception("This Product not found, please try again.");
            }

            // dd($product);
            if($data['quantity'] > $product->stock)
            {
                throw new \Exception("Requested quantity exceeds available stock");
            }

            // dd($data["selected_specifications"]);
            $selectedSpecifications = json_decode($data['selected_specifications'], true);
            // dd($selectedSpecifications);
            $existingCartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->get()
                ->first(function ($cartItem) use ($selectedSpecifications) {
                    return $cartItem->selected_specifications === $selectedSpecifications;
                });

            // dd($existingCartItem);
            if ($existingCartItem)
            {
                $existingCartItem->quantity += $data['quantity'];

                if ($existingCartItem->quantity > $product->stock)
                {
                    throw new \Exception("Requested quantity exceeds available stock");
                }

                $existingCartItem->save();

                DB::commit();
                return redirect()->back()->with('success', 'Product added to cart successfully');
            }
            else
            {
                foreach ($selectedSpecifications as $attributeId => $valueId)
                {
                    $attribute = Attribute::where("department_id" , $product->department_id)
                        ->where("id" , $attributeId)
                        ->first();

                    if (!$attribute)
                    {
                        throw new \Exception("Attribute with ID $attributeId not found");
                    }

                    $value = AttributeValue::where('id', $valueId)
                        ->where('attribute_id', $attributeId)
                        ->first();

                    if (!$value)
                    {
                        throw new \Exception("Value with ID $valueId not valid for attribute $attribute->name");
                    }
                }

                // dd("abed");
                $order = Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $data['quantity'],
                    'selected_specifications' => $selectedSpecifications, // سيخزن JSON تلقائي
                ]);

                if(!$order)
                {
                    throw new \Exception("An error occurred while adding the product to the cart. Please try again.");
                }

                DB::commit();
                return redirect()->back()->with('success', 'Product added to cart successfully');
            }
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard("web")->user();
        // dd($user);
        $orders = Cart::with(['product.images'])
            ->where("user_id" , $user->id)
            ->paginate();

        // dd($orders);
        $allAttributeIds = [];
        $allValueIds = [];

        foreach ($orders as $cart)
        {
            if ($cart->selected_specifications)
            {
                $allAttributeIds = array_merge($allAttributeIds, array_keys($cart->selected_specifications));
                $allValueIds = array_merge($allValueIds, array_values($cart->selected_specifications));
            }
        }

        $attributes = Attribute::whereIn('id', $allAttributeIds)->get()->keyBy('id');
        $values = AttributeValue::whereIn('id', $allValueIds)->get()->keyBy('id');

        $orders->getCollection()->transform(function ($cart) use ($attributes, $values)
        {
            $specs = [];
            if ($cart->selected_specifications)
            {
                foreach ($cart->selected_specifications as $attributeId => $valueId)
                {
                    if (isset($attributes[$attributeId]) && isset($values[$valueId]))
                    {
                        $specs[$attributes[$attributeId]->name] = $values[$valueId]->value;
                    }
                }
            }
            $cart->specs = $specs;
            return $cart;
        });

        // dd($orders->getCollection());
        return view("Client.views.Cart.index" , compact("orders"));
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
    public function destroy($id)
    {
        // dd("abed");
        $user = Auth::guard("web")->user();
        $product = Cart::find($id);
        if(!$product)
        {
            return redirect()->back()->with("error" , "Product not found , please try again.");
        }

        if($product->user_id !== $user->id)
        {
            return redirect()->back()->with("error" , "You are not authorized to perform this action.");
        }

        $product->delete();
        return redirect()->back()->with("success", "Product removed from cart successfully.");
    }
}
