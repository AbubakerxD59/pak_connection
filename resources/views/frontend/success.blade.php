@extends('frontend.main')
@section('body')
    <div class="col-md-12 d-flex justify-content-center">
        <div class="col-md-4">
            <br><br>
            <img src="http://osmhotels.com//assets/check-true.jpg">
            <h3>Dear, <span class="text-primary">
                    {{ $user->full_name }}
                </span></h3>
            <p style="font-size:20px;color:#5C5C5C;">
                You've subscribed Successfully!
            </p>
            <br><br>
        </div>
    </div>
@endsection
