<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapid-Recycle.com</title>
</head>
<body>
    <h1>Your FedEx label is created</h1>
    <p>Thank you for ordering from Rapid Recycle.</p>
    <a href="{{ URL::route('thanks', [ 'order_uuid' => $uuid ]) }}">Show My Offer</a>
</body>
</html>