@foreach ($pricing as $price)
    <span class="btn-sm btn-success rounded rounded-pill">{{ $price->type_text }}:
        <strong>£{{ $price->price }}</strong></span>
@endforeach
