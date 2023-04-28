<!doctype html>
<html>
<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AMovie</title>
    <meta name="description" content="A Template by Gozha.net">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="Gozha.net">

    <!-- Mobile Specific Metas-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">

    <base href="{{asset('')}}">
    <!-- Fonts -->
    <!-- Font awesome - icon font -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Roboto -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,700' rel='stylesheet' type='text/css'>
    <!-- Open Sans -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:800italic' rel='stylesheet' type='text/css'>

    <!-- Stylesheets -->

    <!-- Mobile menu -->
    <link href="user_assets/css/gozha-nav.css" rel="stylesheet" />
    <!-- Select -->
    <link href="user_assets/css/external/jquery.selectbox.css" rel="stylesheet" />

    <!-- Slider Revolution CSS Files -->
    <link rel="stylesheet" type="text/css" href="user_assets/revolution/css/settings.css">
    <link rel="stylesheet" type="text/css" href="user_assets/revolution/css/layers.css">
    <link rel="stylesheet" type="text/css" href="user_assets/revolution/css/navigation.css">

    <!-- Custom -->
    <link href="user_assets/css/style.css?v=1" rel="stylesheet" />


    <!-- Modernizr -->
    <script src="user_assets/js/external/modernizr.custom.js"></script>


</head>

<body>
<div class="wrapper">

    {{-- Header --}}
    @include('user.common.header')

    @yield('content')
    <div class="clearfix"></div>

    {{-- Footer --}}
    @include('user.common.footer')

</div>
<!-- open/close -->
<div class="overlay overlay-hugeinc">

<section class="container">

    <div class="col-sm-4 col-sm-offset-4">
        <button type="button" class="overlay-close">Close</button>
        <form id="login-form" class="login" method='get' novalidate=''>
            <p class="login__title">sign in <br><span class="login-edition">welcome to A.Movie</span></p>

            <div class="social social--colored">
                <a href='#' class="social__variant fa fa-facebook"></a>
                <a href='#' class="social__variant fa fa-twitter"></a>
                <a href='#' class="social__variant fa fa-tumblr"></a>
            </div>

            <p class="login__tracker">or</p>

            <div class="field-wrap">
                <input type='email' placeholder='Email' name='user-email' class="login__input">
                <input type='password' placeholder='Password' name='user-password' class="login__input">

                <input type='checkbox' id='#informed' class='login__check styled'>
                <label for='#informed' class='login__check-info'>remember me</label>
            </div>

            <div class="login__control">
                <button type='submit' class="btn btn-md btn--warning btn--wider">sign in</button>
                <a href="#" class="login__tracker form__tracker">Forgot password?</a>
            </div>
        </form>
    </div>

</section>
</div>

<!-- JavaScript-->
<!-- jQuery 3.1.1-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/external/jquery-3.1.1.min.js"><\/script>')</script>
<!-- Migrate -->
<script src="user_assets/js/external/jquery-migrate-1.2.1.min.js"></script>
<!-- Bootstrap 3-->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

<!-- Slider Revolution core JavaScript files -->
<script type="text/javascript" src="user_assets/revolution/js/jquery.themepunch.tools.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/jquery.themepunch.revolution.min.js"></script>

<!-- Slider Revolution extension scripts. -->
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.actions.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.migration.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<script type="text/javascript" src="user_assets/revolution/js/extensions/revolution.extension.video.min.js"></script>

<!-- Mobile menu -->
<script src="user_assets/js/jquery.mobile.menu.js"></script>
<!-- Select -->
<script src="user_assets/js/external/jquery.selectbox-0.2.min.js"></script>
<!-- Stars rate -->
<script src="user_assets/js/external/jquery.raty.js"></script>

<!-- Form element -->
<script src="user_assets/js/external/form-element.js"></script>
<!-- Form validation -->
<script src="user_assets/js/form.js"></script>

<!-- Twitter feed -->
<script src="user_assets/js/external/twitterfeed.js"></script>

<!-- Custom -->
<script src="user_assets/js/custom.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    init_Home();
});
</script>

</body>
</html>
