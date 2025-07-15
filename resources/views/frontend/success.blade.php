@extends('frontend.main')
@section('body')
    <div class="container success-page-content">
        <i class="bi bi-check-circle"></i>
        <h2>Order Placed Successfully</h2>
        <h1>
            Welcome to Pak Connection!
        </h1>
        <p>
            We're excited to have you on board as a valued member.
            Your order has been received and is being processed.
            You'll soon receive a confirmation email with further details.
        </p>
        <p>
            <span class="h4">Membership ID:</span>
            <span class="font-weight-bold h4">{{ $user->membership_id }}</span>
        </p>
        <h2>You may start by calling us as well</h2>
        <a href="tel:+9252000111" class="btn btn-primary"><i class="fa fa-phone rotate-right"></i>+92-52-000111 (11am-7pm)</a>
        <div class="mt-4"><a href="{{ route('frontend.member.home') }}" class="btn btn-primary"></i>Go to Home</a></div>
    </div>
@endsection
