<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')

    <style>
        .div_center{
            text-align: center;
            padding-top: 40px;
        }

        .font_size{
            font-size: 40px;
            padding-bottom: 40px;
        }

        .text_color{
            color: black;
            padding-bottom: 20px;
        }

        label{
            display: inline-block;
            width: 200px;
        }

        .div_design{
            padding-bottom: 15px;
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

            <div class="div_center">
                <h1 class="font_size">Update Product</h1>

                <form action="{{url('/update_product_confirm', $product->id)}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="div_design">
                        <label>Product title</label>
                        <input class="text_color" type="text" name="title" value="{{$product->title}}" required="">
                    </div>

                    <div class="div_design">
                        <label>Product Description</label>
                        <input class="text_color" type="text" name="description" value="{{$product->description}}" required="">
                    </div>

                    <div class="div_design">
                        <label>Product Price</label>
                        <input class="text_color" type="number" name="price" value="{{$product->price}}" required="">
                    </div>

                    <div class="div_design">
                        <label>Discount Price</label>
                        <input class="text_color" type="number" name="discount_price" value="{{$product->discount_price}}">
                    </div>

                    <div class="div_design">
                        <label>Product Quantity</label>
                        <input class="text_color" type="number" min="0" name="quantity" value="{{$product->quantity}}" required="">
                    </div>

                    <div class="div_design">
                        <label>Product Catagory</label>
                        <select class="text_color" name="catagory" id="" required="">
                            <option value="{{$product->catagory}}" selected="">{{$product->catagory}}</option>
                            @foreach($catagory as $catagory)
                                @if($catagory->catagory_name != $product->catagory)
                                    <option value="{{$catagory->catagory_name}}">{{$catagory->catagory_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="div_design">
                        <label>Current Product Image</label>
                        <img style="margin: auto;" height="100" width="100" src="/product/{{$product->image}}" alt="">
                    </div>

                    <div class="div_design">
                        <label>Change Product Image</label>
                        <input type="file" name="image">
                    </div>

                    <div class="div_design">
                        <input type="submit" value="Update Product" class="btn btn-primary">
                    </div>
                </form>


            </div>
        </div>
    </div>



    <!-- container-scroller -->

    <!-- plugins:js -->
    @include('admin.script')
    <!-- End custom js for this page -->
</body>
</html>
