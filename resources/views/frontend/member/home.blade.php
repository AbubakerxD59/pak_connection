@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <p class="text-center">
            <span class="h4">Package:</span>
            <span class="font-weight-bold h4">{{ $package->name }}</span>
        </p>
        <div class="row">
            @foreach ($features as $feature)
                <div class="col-md-4 p-3 text-center">
                    <div class="pointer">
                        <img src="{{ $feature->icon }}" alt="" width="200px" class="rounded">
                    </div>
                    <div class="my-3">
                        <span>
                            <strong>{{ strtoupper($feature->name) }}</strong>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
