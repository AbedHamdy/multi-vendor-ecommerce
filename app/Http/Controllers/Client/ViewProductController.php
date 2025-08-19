<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Product;
use Illuminate\Http\Request;

class ViewProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $AllDepartments = Department::all();

        $productsQuery = Product::with(['images' => function($query) {
            $query->where('is_main', true);
        }, 'department']);

        if ($request->has('department_id') && $request->department_id != '')
        {
            $productsQuery->where('department_id', $request->department_id);
        }

        $productsQuery->where('is_active', true);

        $products = $productsQuery->paginate()->appends($request->query());
        // dd($products);

        return view("Client.views.Product.index", compact("products", "AllDepartments"));
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
        $product = Product::with([
            'images',
            'department',
            'attributeValues.attribute',
            'attributeValues.attributeValue'
        ])
        ->where("is_active", "1")
        ->where("id", $id)
        ->first();

        if(!$product)
        {
            return redirect()->route("view_product")->with("error", "Product not found, please try again.");
        }

        // dd($product->attributeValues);

        return view("Client.views.Product.show" , compact("product"));
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
