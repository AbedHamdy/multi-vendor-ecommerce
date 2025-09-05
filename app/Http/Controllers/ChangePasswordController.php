<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function edit()
    {
        // dd("abed");
        return view("change_password");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $user = auth()->guard('admin')->user() ?: auth()->guard('vendor')->user();

        if (!$user || !Hash::check($data['current_password'], $user->password))
        {
            return back()->with("error", 'Current password is incorrect');
        }

        $user->password =Hash::make($data["new_password"]);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
