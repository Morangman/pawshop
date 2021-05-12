<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapid-Recycle.com</title>

    <style type="text/css">
        .quote {
            border-left: 2px solid #000;
            padding-left: 15px;
            margin-top: 20px;
        }

        #signature {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <p>{{ $data['text'] }}</p>

    @if(isset($data['last_message']))
        <div class="quote">{{ $data['last_message'] }}</div>
    @endif

    <div id="signature">
        <p>The Rapid Recycle Team</p>
    </div>
</body>
</html>