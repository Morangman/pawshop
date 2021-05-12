<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapid-Recycle.com</title>

    <style type="text/css">
        .quote {
            border-left: 2px solid #000;
            padding-left: 15px
        }

        #signature {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    @if(isset($data['last_message']))
        <div class="quote">{{ $data['last_message'] }}</div>
    @endif

    <p>{{ $data['text'] }}</p>

    <div id="signature">
        <font style="font-family:'Century Gothic', Arial, sans-serif; font-size: 9pt; color:#333;line-height:0.5;">
            <p id="name" style="color: #126de5; font-size:120%; font-weight:bolder; line-height: 0.5;">Rapid Recycle</p>
            <p id="position">©2021 Rapid Recycle</p>
            <p id="tel">1730 E Warner Rd, Suite 7, Tempe, AZ 85284, USA</p>
        </font>
    </div>
</body>
</html>