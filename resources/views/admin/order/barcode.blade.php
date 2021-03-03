<div class="barcode">
    {!! $barcode !!}
    <p>{{ $order->getAttribute('address')['name'].', '.$order->getAttribute('address')['city'].', '.$order->getAttribute('address')['address1'] }}</p>
</div>
