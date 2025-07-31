@extends('frontend.main')
@section('body')
    <link rel="stylesheet" href="{{ asset('assets/css/stepper.css') }}">
    <section class="membership-portal container">
        @forelse ($features as $feature)
            <div class="container stepper-container mb-2">
                <div class="col-12">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <span class="h4 font-weight-bold">{{ $feature->getService() }}</span>
                            <span>{!! service_book_status($feature->status) !!}</span>
                        </div>
                        <div class="row">
                            <span>Date: </span>
                            <p class="font-weight-bold ml-1">{{ date('Y-m-d', strtotime($feature->created_at)) }}</p>
                        </div>
                    </div>
                    <div class="stepper-wrapper">
                        @foreach (getbookedServicestatus() as $key => $status)
                            <!-- Step 1 (Completed) -->
                            @php
                                $class = '';
                                if ($feature->status == $key) {
                                    $class = 'active';
                                }
                                if ($feature->status > $key) {
                                    $class = 'completed';
                                }
                            @endphp
                            <div class="stepper-item {{ $class }}" title="{{ $status }}">
                                <div class="step-circle">{{ $key }}</div>
                                <div class="step-text">{{ $status }}</div>
                            </div>
                        @endforeach
                        {{-- <!-- Step 2 (Active) -->
                        <div class="stepper-item active">
                            <div class="step-circle">2</div>
                            <div class="step-text">Step Two</div>
                        </div>
                        <!-- Step 3 -->
                        <div class="stepper-item">
                            <div class="step-circle">3</div>
                            <div class="step-text">Step Three</div>
                        </div>
                        <!-- Step 4 -->
                        <div class="stepper-item">
                            <div class="step-circle">4</div>
                            <div class="step-text">Step Four</div>
                        </div>
                        <!-- Step 5 -->
                        <div class="stepper-item">
                            <div class="step-circle">5</div>
                            <div class="step-text">Step Five</div>
                        </div>
                        <!-- Step 6 -->
                        <div class="stepper-item">
                            <div class="step-circle">6</div>
                            <div class="step-text">Step Six</div>
                        </div>
                        <!-- Step 7 -->
                        <div class="stepper-item">
                            <div class="step-circle">7</div>
                            <div class="step-text">Step Seven</div>
                        </div>
                        <!-- Step 8 -->
                        <div class="stepper-item">
                            <div class="step-circle">8</div>
                            <div class="step-text">Step Eight</div>
                        </div>
                        <!-- Step 9 -->
                        <div class="stepper-item">
                            <div class="step-circle">9</div>
                            <div class="step-text">Step Nine</div>
                        </div>
                        <!-- Step 10 -->
                        <div class="stepper-item">
                            <div class="step-circle">10</div>
                            <div class="step-text">Step Ten</div>
                        </div>
                        <!-- Step 11 -->
                        <div class="stepper-item">
                            <div class="step-circle">11</div>
                            <div class="step-text">Step Eleven</div>
                        </div> --}}
                    </div>
                </div>
            </div>
        @empty
            <span class="h5 font-weight-bold">NO SERVICE BOOKED YET</span>
        @endforelse
    </section>
@endsection
