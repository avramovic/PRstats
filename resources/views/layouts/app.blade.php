<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"><![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
    <!-- Website Template designed by www.downloadwebsitetemplates.co.uk -->
    <meta charset="UTF-8">
    <title>@yield('title') &bull; PR stats</title>
    <meta name="description" content="Alan Ford stripoteka">
    <meta name="keywords" content="alan ford, alan, ford, stripovi, android, aplikacija">
    <link rel="shortcut icon" href="/images/logo.png" type="image/png"/ >
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- link rel="shortcut icon" href="/images/ico/favicon.png" -->
    <!--[if IE]><![endif]-->
    <link rel="stylesheet" href="/css/style.css">

    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>

<div id="outercont" class="clearfix">
    <div id="innercont" class="clearfix">

        <div id="content" class="bodycontainer clearfix">
            <h1 style="float: left">@yield('title')</h1>
            <a href="{{ url('/') }}" style="float: right"><img src="/images/logo.png" class="logo" /></a>

            <div class="clear"></div>
            <div class="left">
                @yield('content')
            </div>

            <div class="right">
                <div class="darkbg hidden">
                @yield('right')
                </div>
            </div>
        </div>

        <div id="copyright" class="bodycontainer clearfix">

            <p>Copyright &copy; {{ date('Y') }}, <a href="http://www.avramovic.info" target="_blank">Nemanja Avramovic</a> a.k.a.
                <a href="#">Sgt_Baker</a> </p>

        </div>

    </div>
</div>

<script src="/js/jquery.js"></script>
<script src="/bower_components/cheet.js/cheet.min.js"></script>
<script src="/js/scripts.js"></script>
@yield('scripts')

</body>
</html>