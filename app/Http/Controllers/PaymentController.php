<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\PaymentSuccessMail;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{

    public function success(Request $request, $package_id)
    {
        // \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $user = Auth::guard("web")->user();
        // $packageId = $request->query('package_id');

        $password = Str::random(10);
        DB::beginTransaction();
        try
        {
            $vendor = Vendor::create([
                "name" => $user->name,
                "email" => $user->email,
                "password" => Hash::make($password),
                "package_id"=> $package_id,
            ]);

            $package = Package::find($package_id);

            $pay = Subscription::create([
                'vendor_id' => $vendor->id,
                'package_id' => $package_id,
                'payment_status' => 'paid',
                'payment_reference' => session('stripe_session_id'),
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addMonths($package->duration),
            ]);

            // dd($pay);

            Mail::to($user->email)->send(new PaymentSuccessMail($user->email, $password));
            $user->delete();
            DB::commit();
            Auth::guard("web")->logout();
            return view('Client.views.Package.success');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return redirect()->route('view_packages')->with('error', "Error send mail to vendor.");
        }
        // $request->session()->put('has_paid', true);
    }

    public function cancel()
    {
        return view('Client.views.Package.cancel');
    }
}
