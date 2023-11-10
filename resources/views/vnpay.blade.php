<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form name="vnpay" action="{{ route('vnpayCheckOut') }}" method="POST">
        @csrf
        
        <button type="submit" name="redirect">Nhấn đây vnpay</button>
    </form>
    <br>
    <form name="vnpay" action="{{ route('momoCheckOut') }}" method="POST">
        @csrf
        <button type="submit" name="payUrl">Nhấn đây momo</button>
    </form>
</body>
</html>