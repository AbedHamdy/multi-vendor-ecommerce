<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckLoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Department;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("Client.views.login");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function check(CheckLoginRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        switch($data["login-role"])
        {
            case "admin":
                if (Auth::guard('admin')->attempt([
                        'email' => $data['singin-email'],
                        'password' => $data['singin-password'],
                    ]))
                {
                    return redirect()->route('dashboard');
                }

                return back()->with('error', 'Invalid admin credentials.')->withInput();
            case "user":
                if(Auth::guard("web")->attempt([
                        'email' => $data['singin-email'],
                        'password' => $data['singin-password'],
                    ]))
                {
                    return redirect()->route("home");
                }
                return back()->with('error', 'Invalid admin credentials.')->withInput();
            case "vendor":
                if(Auth::guard("vendor")->attempt([
                        'email' => $data['singin-email'],
                        'password' => $data['singin-password'],
                    ]))
                {
                    $vendor = Auth::guard('vendor')->user();
                    if ($vendor->department_id == null)
                    {
                        $departments = Department::get();
                        return view("Vendor.choose-department" , compact("departments"));
                    }
                    return redirect()->route("dashboard_vendor");
                }
            default:
            return back()->with('error', 'Invalid login role selected.')->withInput();
        }
    }

    public function registerPage()
    {
        return view("Client.views.register");
    }

    /**
     * Store a newly register resource in storage.
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $user = User::create([
            "name" => $data["register-name"],
            "email" => $data["register-email"],
            "password" => Hash::make($data["register-password"]),
        ]);

        Auth::guard('web')->login($user);

        return redirect()->route("home")->with("success" , "User created successfully");
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
    public function logoutVendor()
    {
        Auth::guard('vendor')->logout();

        return redirect()->route('login')->with('success', "Logout successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('login')->with('success', "Logout successfully");
    }

    public function logoutUser()
    {
        Auth::guard('web')->logout();

        return redirect()->route('login')->with('success', "Logout successfully");
    }
}
