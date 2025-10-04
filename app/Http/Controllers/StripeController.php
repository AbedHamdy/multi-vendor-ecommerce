<?php

    namespace App\Http\Controllers;

    use App\Models\Package;
    use Illuminate\Http\Request;

    class StripeController extends Controller
    {
        /**
         * Handle the incoming request to initiate a Stripe checkout session.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\RedirectResponse
         */
        public function checkout(Request $request, $id)
        {
            // $method = $request->input('payment_method');
            $package = Package::find($id);
            if(!$package)
            {
                return redirect()->back()->with("error" , "Package not found.");
            }
            // dd(env('STRIPE_SECRET'));

            // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ["card"],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $package->name,
                        ],
                        'unit_amount' => intval($package->price * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success' , ['package_id' => $package->id]),
                // 'success_url' => url('/payment/success/' . $package->id),
                'cancel_url' => route('payment.cancel'),
            ]);

            // dd($session);
            session(['stripe_session_id' => $session->id]);

            return redirect($session->url);
        }
    }
