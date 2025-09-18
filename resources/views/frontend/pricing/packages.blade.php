<div class="packages" id="package_price-{{ $key }}" style="{{ $key != 1 ? 'display:none;' : '' }}">
    @foreach ($prices->where('type', $key) as $price)
        <article class="package">
            <div class="package-box">
                <header class="packages-header">
                    <h3>{{ $price->package->name }}</h3>
                    <span class="font-weight-bold h5">
                        {{ '£' . $price->price }}
                    </span>
                    for
                    <span>
                        {{ $price->type_text }}
                    </span>
                </header>
                <ul class="package-list">
                    @foreach ($price->package->checkFeatures() as $key => $feature)
                        @if ($key == 'include')
                            <span><strong>Includes:</strong></span>
                            @foreach ($feature as $include)
                                <li class="parent-li">
                                    <span class="circle-icon bg-primary">
                                        <i class="fa fa-check"></i>
                                    </span>
                                    {{ $include }}
                                </li>
                            @endforeach
                        @elseif($key == 'extra')
                            <span><strong>Plus:</strong></span>
                            @foreach ($feature as $extra)
                                <li class="parent-li">
                                    <span class="circle-icon bg-primary">
                                        <i class="fa fa-check"></i>
                                    </span>
                                    {{ $extra }}
                                </li>
                            @endforeach
                        @else
                            <li>
                                <span class="circle-icon bg-primary">
                                    <i class="fa fa-check"></i>
                                </span>
                                {{ $feature }}
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            @if (!empty($price->package->is_user_package) && !$price->package->is_expired)
                <p class="text-success">Ends on:
                    {{ \Carbon\Carbon::parse($price->package->pkg_end_time)->format('F d, Y h:i A') }}</p>
            @else
                <a href="{{ route('frontend.buy_memebership', $price->id) }}" class="btn btn-primary w-100">Buy
                    Now</a>
            @endif
        </article>
    @endforeach
</div>
