<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Package;
use App\Models\Product;
use App\Models\productAttributeValue;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard("vendor")->user();
        $products = Product::where('vendor_id', $user->id)->paginate();
        return view('Vendor.Product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::guard("vendor")->user();

        $subscription = $user->subscriptions()
        ->where('starts_at', '<=', now())
        ->where('ends_at', '>=', now())
        ->latest()
        ->first();

        if (!$subscription)
        {
            return redirect()->route('package')->with("error", "You don't have an active subscription.");
        }

        $package = $subscription->package;
        $productCount = Product::where("vendor_id", $user->id)->count();

        if($productCount >= $package->max_products)
        {
            return redirect()->back()->with("error" , "You have reached the maximum number of products allowed in your current package.");
        }

        $attributes = Attribute::with("values")
            ->where("department_id" , $user->department_id)
            ->get();

        // dd($attributes);
        return view("Vendor.Product.create" , compact("attributes"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $user = Auth::guard("vendor")->user();

        $subscription = $user->subscriptions()
        ->where('starts_at', '<=', now())
        ->where('ends_at', '>=', now())
        ->latest()
        ->first();

        if (!$subscription)
        {
            return redirect()->route('package')->with("error", "You don't have an active subscription.");
        }

        $package = $subscription->package;
        $productCount = Product::where("vendor_id", $user->id)->count();

        if ($productCount >= $package->max_products)
        {
            return redirect()->route("package")->with("error", "You have reached the maximum number of products allowed in your current package.");
        }
        
        DB::beginTransaction();
        try
        {
            $product = Product::create([
                "name" => $data["name"],
                "price" => $data["price"],
                "discount" => $data["discount"],
                "stock" => $data["stock"],
                "description" => $data["description"],
                "vendor_id" => $user->id,
                "department_id" => $user->department_id,
            ]);

            if(!$product)
            {
                throw new \Exception("Failed to create product. Please try again later.");
            }

            foreach ($data['images'] as $index => $image)
            {
                $random = rand(1000, 100000);
                $time = now()->format('YmdHis');
                $ext = $image->getClientOriginalExtension();
                $fileName = $time . '_' . $random . '.' . $ext;

                $destinationPath = public_path('images/products');
                $image->move($destinationPath, $fileName);

                $newImage = ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $fileName,
                    'is_main' => $index === 0 ? true : false,
                ]);

                if(!$newImage)
                {
                    throw new \Exception("Failed to create product image. Please try again later.");
                }
            }

            foreach ($data['attributes'] as $attributeData)
            {
                foreach ($attributeData['value_ids'] as $valueId)
                {
                    $productAttr = productAttributeValue::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attributeData['attribute_id'],
                        'attribute_value_id' => $valueId,
                    ]);

                    if(!$productAttr)
                    {
                        throw new \Exception("Failed to create product attribute. Please try again later.");
                    }
                }
            }

            DB::commit();
            return redirect()->route("product")->with("success" , "Product created successfully.");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with("error" , "Failed to create product. Please try again later.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::guard("vendor")->user();
        $product = Product::with(["mainImage" , "images" , "attributeValues.attribute"  ,"attributeValues.attributeValue"])
            ->where("vendor_id" , $user->id)
            ->where("id" , $id)
            ->first();

        if(!$product)
        {
            return redirect()->back()->with("error" , "Product not found or you don't have permission to view it.");
        }

        // dd($product);
        return view("Vendor.Product.show" , compact("product"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::guard("vendor")->user();
        $product = Product::with(["mainImage" , "attributeValues.attribute"  ,"attributeValues.attributeValue"])
            ->where("vendor_id" , $user->id)
            ->where("id" , $id)
            ->first();

        if(!$product)
        {
            return redirect()->back()->with("error" , "Product not found or you don't have permission to view it.");
        }

        $attributes = Attribute::with("values")
            ->where("department_id" , $user->department_id)
            ->get();
        // dd($product);
        return view("Vendor.Product.edit" , compact("product" , "attributes"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $user = Auth::guard("vendor")->user();
        $product = Product::where("vendor_id" , $user->id)
            ->where("id" , $id)
            ->first();

        if(!$product)
        {
            return redirect()->back()->with("error" , "Product not found or you don't have permission to view it.");
        }

        $data = $request->validated();
        // dd($data);
        DB::beginTransaction();
        try
        {
            $product->update([
                "name" => $data["name"],
                "price" => $data["price"],
                "discount" => $data["discount"],
                "stock" => $data["stock"],
                "description" => $data["description"],
            ]);

            if (isset($data['images']) && is_array($data['images']) && count($data['images']) > 0)
            {
                if ($product->images && count($product->images) > 0)
                {
                    foreach ($product->images as $oldImage)
                    {
                        $oldImagePath = public_path('images/products/' . $oldImage->image);
                        if (file_exists($oldImagePath))
                        {
                            unlink($oldImagePath);
                        }

                        $oldImage->delete();
                    }
                }

                foreach ($data['images'] as $index => $image)
                {
                    $random = rand(1000, 100000);
                    $time = now()->format('YmdHis');
                    $ext = $image->getClientOriginalExtension();
                    $fileName = $time . '_' . $random . '.' . $ext;

                    $destinationPath = public_path('images/products');
                    $image->move($destinationPath, $fileName);

                    $newImage = ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $fileName,
                        'is_main' => $index === 0 ? true : false,
                    ]);

                    if (!$newImage)
                    {
                        throw new \Exception("Failed to create product image.");
                    }
                }
            }

            $product->attributeValues()->delete();
            foreach ($data['attributes'] as $attributeData)
            {
                foreach ($attributeData['value_ids'] as $valueId)
                {
                    $productAttr = productAttributeValue::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attributeData['attribute_id'],
                        'attribute_value_id' => $valueId,
                    ]);

                    if (!$productAttr)
                    {
                        throw new \Exception("Failed to update product attribute.");
                    }
                }
            }

            DB::commit();
            return redirect()->route("product")->with("success", "Product updated successfully.");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with("error", "Failed to update product. Please try again later." . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::guard("vendor")->user();

        $product = Product::where('id', $id)
            ->where('vendor_id', $user->id)
            ->first();

        if (!$product)
        {
            return redirect()->back()->with('error', 'Product not found or you don\'t have permission to delete it.');
        }

        foreach ($product->images as $img)
        {
            if ($img->image_path && file_exists(public_path($img->image_path)))
            {
                unlink(public_path($img->image_path));
            }

            $img->delete();
        }

        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
