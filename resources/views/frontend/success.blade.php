@extends('frontend.main')
@section('body')
    <div class="container success-page-content">
        <i class="bi bi-check-circle"></i>
        <h2>Awesome payment was successful!</h2>
        <h1>
            Dear, <span class="customer-name green-color">
                {{ $user->full_name }}
            </span>
        </h1>
        <p>
            Thank you, we have received you payment, You may now initiate the chat with payment Transuction ID or Please email us your confirmation Pyament ID so that we can proceed further with your request.
        </p>
        <h2>You may start by calling us as well</h2>
        <a href="tel:+9252000111" class="btn btn-primary">+92-52-000111 (11am-7pm)</a> 
    </div>
@endsection
