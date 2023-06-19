<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')

    <style>
        .title_deg {
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            padding-bottom: 20px;
        }

        .table_deg {
            border: 1px solid white;
            width: 100%;
            margin: auto;
            text-align: center;
        }

        th {
            background-color: skyblue;
        }

        th, td{
            padding: 5px;
        }
    </style>
</head>
<body>

<div class="container-scroller">
    <!-- partial:partials/_sidebar.html -->
    @include('admin.sidebar')

    <!-- partial -->
    @include('admin.header')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">

            @if(Session::has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    {{Session::get('message')}}
                </div>
            @endif

            <h1 class="title_deg">All Orders</h1>

            <div style="margin: 20px auto;">
                <form action="{{url('search')}}" method="get">
                    @csrf
                    <input style="color: black" type="text" name="search" placeholder="search for something">
                    <input type="submit" value="Search" class="btn btn-outline-primary">
                </form>
            </div>

            <table class="table_deg">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Product title</th>
                    <th>Quantity</th>
                    <th>Price</th>

                    <th>Payment status</th>
                    <th>Delivery Status</th>
                    <th>Image</th>
                    <th>Delivered</th>
                    <th>Print PDF</th>
                    <th>Send email</th>
                </tr>

                @forelse($orders as $order)
                    <tr>
                        <td>{{$order->name}}</td>
                        <td>{{$order->email}}</td>
                        <td>{{$order->address}}</td>
                        <td>{{$order->phone}}</td>
                        <td>{{$order->product_title}}</td>
                        <td>{{$order->quantity}}</td>
                        <td>{{$order->price}}</td>

                        <td>{{$order->payment_status}}</td>
                        <td>{{$order->delivery_status}}</td>
                        <td>
                            <img style="width: 100px; height: 100px;" src="/product/{{$order->image}}" alt="">
                        </td>
                        <td>
                            @if($order->delivery_status == 'processing')
                                <a onclick="return confirm('Are you sure this product be delievered.')" class="btn btn-primary" href="{{url('/delivered', $order->id)}}">Delivered</a>
                            @else
                                <p style="color: green;">Delivered</p>
                            @endif

                        </td>
                        <td>
                            <a class="btn btn-secondary" href="{{url('/print_pdf', $order->id)}}">Print PDF</a>
                        </td>
                        <td>
                            <a class="btn btn-info" href="{{url('send_email', $order->id)}}">Send email</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="50">No data available</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>


    <!-- plugins:js -->
    @include('admin.script')
    <!-- End custom js for this page -->
</body>
</html>
