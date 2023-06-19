<!DOCTYPE html>
<html>
<head>
    <!-- Basic -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <!-- Site Metas -->
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <link rel="shortcut icon" href="images/favicon.png" type="">
    <title>Famms - Fashion HTML Template</title>
    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/bootstrap.css')}}"/>
    <!-- font awesome style -->
    <link href="{{asset('home/css/font-awesome.min.css')}}" rel="stylesheet"/>
    <!-- Custom styles for this template -->
    <link href="{{asset('home/css/style.css')}}" rel="stylesheet"/>
    <!-- responsive style -->
    <link href="{{asset('home/css/responsive.css')}}" rel="stylesheet"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
            integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
@include('sweetalert::alert');

<div class="hero_area">
    <!-- header section strats -->
    @include('home.header')
    <!-- end header section -->

    <!-- slider section -->
    @include('home.slider')
    <!-- end slider section -->
</div>

<!-- why section -->
@include('home.why')
<!-- end why section -->

<!-- arrival section -->
@include('home.new_arrival')
<!-- end arrival section -->

<!-- product section -->
@include('home.product')
<!-- end product section -->

{{--Comment and reply system starts here--}}
<div style="text-align: center; padding-bottom: 30px;">
    <h1 style="font-size: 30px; text-align: center; padding-top: 50px; padding-bottom: 30px">Comemnts</h1>
    <form action="{{url('add_comment')}}" method="post">
        @csrf
        <textarea style="width: 600px" name="comment" id="" cols="30" rows="10"
                  placeholder="Comment something here."></textarea>
        <br>
        <input type="submit" class="btn btn-primary" value="Comment">
    </form>
</div>

<div style="padding-left: 30%;">
    <h1 style="">All comments</h1>
    @foreach($comments as $comment)
        <div>
            <b>{{$comment->name}}</b>
            <p>{{$comment->comment}}</p>
            <a href="javascript:void(0);" onclick="reply(this)" data-Commentid="{{$comment->id}}">Reply</a>

            @foreach($replies as $reply)
                @if($reply->comment_id == $comment->id)
                    <div style="padding-left: 3%; padding-bottom: 10px; padding-top: 10px;">
                        <b>{{$reply->name}}</b>
                        <p>{{$reply->reply}}</p>
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach


    {{--Reply text box--}}
    <div style="display: none;" class="replyDiv">
        <form action="{{url('add_reply')}}" method="post">
            @csrf
            <input type="text" id="commentId" name="commentId" hidden="">
            <textarea style="width: 500px" name="reply" placeholder="Reply ..."></textarea>
            <br>
            <button type="submit" class="btn btn-warning">Reply</button>
            <a href="javascript:void(0)" class="btn btn-secondary" onclick="reply_close(this)">Close</a>
        </form>
    </div>
</div>
{{--End Comment and reply system starts here--}}

<!-- subscribe section -->
@include('home.subscribe')
<!-- end subscribe section -->

<!-- client.blade.php section -->
@include('home.client')
<!-- end client.blade.php section -->

<!-- footer start -->
@include('home.footer')
<!-- footer end -->

<div class="cpy_">
    <p class="mx-auto">© 2021 All Rights Reserved By <a href="https://html.design/">Free Html Templates</a><br>

        Distributed By <a href="https://themewagon.com/" target="_blank">ThemeWagon</a>

    </p>
</div>

<script type="text/javascript">
    function reply(caller) {
        document.getElementById('commentId').value = $(caller).attr('data-Commentid');
        $('.replyDiv').insertAfter($(caller));
        $('.replyDiv').show();
    }

    function reply_close(caller) {
        $('.replyDiv').hide();
    }
</script>

{{--Return Scroll position after reload--}}
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        var scrollpos = localStorage.getItem('scrollpos');
        if (scrollpos) window.scrollTo(0, scrollpos);
    });

    window.onbeforeunload = function(e) {
        localStorage.setItem('scrollpos', window.scrollY);
    };
</script>


<!-- jQery -->
<script src="home/js/jquery-3.4.1.min.js"></script>
<!-- popper js -->
<script src="home/js/popper.min.js"></script>
<!-- bootstrap js -->
<script src="home/js/bootstrap.js"></script>
<!-- custom js -->
<script src="home/js/custom.js"></script>
</body>
</html>
