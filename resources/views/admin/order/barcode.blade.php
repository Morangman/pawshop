<div class="barcode">
    <img src="data:image/png;base64,{!! $barcode !!}" alt="barcode" /><br>
    <p>{{ $order->getAttribute('address')['name'].', '.$order->getAttribute('address')['city'].', '.$order->getAttribute('address')['address1'] }}</p>
</div>
