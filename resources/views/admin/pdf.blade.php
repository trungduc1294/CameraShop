<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF</title>
</head>
<body>
<h1>Order Details</h1>

<h3>Order ID: {{$order->id}}</h3>

<h3>Customer name: {{$order->name}}</h3>
<h3>Customer email: {{$order->email}}</h3>
<h3>Customer phone: {{$order->phone}}</h3>
<h3>Customer addrress: {{$order->address}}</h3>

<h3>Product name: {{$order->product_title}}</h3>
<h3>Product quantity: {{$order->quantity}}</h3>
<h3>Product price: {{$order->price}}</h3>
<h3>Payment status: {{$order->payment_status}}</h3>

<img style="width: 200px; height: 200px" src="product/{{$order->image}}" alt="">

</body>
</html>
