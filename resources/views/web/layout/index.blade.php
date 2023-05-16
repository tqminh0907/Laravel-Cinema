<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HuuMinh</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">

    <base href="{{asset('')}}">
    <!-- Fonts -->
    <!-- Font awesome - icon font -->
    <link href="\web_assets\fonts\fontawesome\css\all.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>

    <div class="wrapper">

        {{-- Header --}}
        @include('web.common.header')

        {{-- Search  --}}
        @include('web.common.search')

        {{-- Warning  --}}
        @if(count($errors) > 0)
        <div class="alert alert-danger">
            @foreach($errors->all() as $arr)
            {{ $arr }}<br>
            @endforeach
        </div>
        @endif
        @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
        @endif


        @yield('content')

        {{-- Footer --}}
        @include('web.common.footer')

    </div>

    {{-- login --}}
    @include('web.common.login')



<<<<<<< HEAD
    <!-- JavaScript -->
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- FontAwesome -->
    {{--<script src="https://kit.fontawesome.com/3212388768.js" crossorigin="anonymous"></script>--}}


    <script>
        $(".nav .nav-item .nav-link").on("click", function() {
            $(".nav-item").find(".active").removeClass("active").addClass("link-secondary");
            $(this).addClass("active").removeClass("link-secondary");
        });
    </script>
=======
<!-- JavaScript -->
<!-- JQuery -->
<script
    src="https://code.jquery.com/jquery-3.6.4.js"
    integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
    crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous">s</script>

<!-- FontAwesome -->
{{--<script src="https://kit.fontawesome.com/3212388768.js" crossorigin="anonymous"></script>--}}
@yield('js')
>>>>>>> web
</body>

</html>
