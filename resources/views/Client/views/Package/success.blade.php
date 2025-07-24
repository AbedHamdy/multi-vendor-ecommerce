@extends('Client.layouts.app')

@section('title', 'Payment Success')

@section('content')
<div class="text-center mt-5">
    <h2 class="text-success">Payment Successful!</h2>
    <p>Thank you for subscribing to the package.</p>
    <p>Now, please continue entering the details of the products you want to promote.</p>

    <div class="alert alert-info mt-4 mx-auto" style="max-width: 600px;">
        <strong>Note:</strong> Please check your email inbox. We've sent you your <strong>username</strong> and <strong>password</strong> to access your account.
    </div>
</div>
@endsection
