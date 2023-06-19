<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/public">
    @include('admin.css')

    <style>
        label{
            display: inline-block;
            width: 200px;
        }

    </style>
</head>
<body>

<div class="container-scroller">
    <!-- partial:partials/_sidebar.html -->
    @include('admin.sidebar')

    <!-- partial -->
    @include('admin.header')

    <div class="main-panel">
        <div class="content-wrapper">

            @if(Session::has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    {{Session::get('message')}}
                </div>
            @endif

            <h1 style="font-size: 24px; font-weight: 600;">Send to: {{$order->email}}</h1>

            <form action="{{url('send_user_email', $order->id)}}" method="post">
                @csrf
                <div style="margin-top: 10px">
                    <label for="greeting">Email Greeting: </label>
                    <input style="color: black;" type="text" name="greeting">
                </div>
                <div style="margin-top: 10px">
                    <label for="firstline">Email First line: </label>
                    <input style="color: black;" type="text" name="firstline">
                </div>
                <div style="margin-top: 10px">
                    <label for="body">Email Body: </label>
                    <input style="color: black;" type="text" name="body">
                </div>
                <div style="margin-top: 10px">
                    <label for="button">Email Button Name: </label>
                    <input style="color: black;" type="text" name="button">
                </div>
                <div style="margin-top: 10px">
                    <label for="url">Email Url: </label>
                    <input style="color: black;" type="text" name="url">
                </div>
                <div style="margin-top: 10px">
                    <label for="lastline">Email Lastline: </label>
                    <input style="color: black;" type="text" name="lastline">
                </div>
                <div style="margin-top: 10px">
                    <input type="submit" value="Send Email" class="btn btn-primary">
                </div>

            </form>
        </div>
    </div>



    <!-- plugins:js -->
    @include('admin.script')
    <!-- End custom js for this page -->
</body>
</html>
