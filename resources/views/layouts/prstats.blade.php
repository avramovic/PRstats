<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Project Reality statistics">
    <meta name="author" content="Nemanja Avramovic">
    <meta name="keyword" content="projct reality, project, reality, game, battlefield">
    <title>@yield('title') &bull; PRstats</title>

    <!-- Favicons -->
    <link href="/img/logo.png" rel="icon">
    <link href="/img/logo.png" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--external css-->
    <link href="/lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/style-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/lib/gritter/css/jquery.gritter.css" />
    <script src="/lib/chart-master/Chart.js"></script>

    @yield('header')
</head>

<body>
<section id="container">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
    @include('partials.header')
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    @include('partials.sidebar')
    <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            @hasSection('subtitle')
                <p>&nbsp;</p>
            <h3><i class="fa fa-angle-right"></i>
                @yield('subtitle')
            </h3>
            @endif
            <div class="row mt">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </section>
        <!-- /wrapper -->
    </section>
    <!-- /MAIN CONTENT -->
    <!--main content end-->
    <!--footer start-->
    @include('partials.footer')
    <!--footer end-->
</section>
<!-- js placed at the end of the document so the pages load faster -->
<script src="/lib/jquery/jquery.min.js"></script>
<script src="/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="/lib/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/lib/jquery.ui.touch-punch.min.js"></script>
<script class="include" type="text/javascript" src="/lib/jquery.dcjqaccordion.2.7.js"></script>
<script src="/lib/jquery.scrollTo.min.js"></script>
<script src="/lib/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/lib/jquery.sparkline.js"></script>
<script type="text/javascript" src="/lib/gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="/lib/gritter-conf.js"></script>
<!--common script for all pages-->
<script src="/lib/common-scripts.js"></script>
<script src="/lib/sparkline-chart.js"></script>
@if(\Carbon\Carbon::now()->isBetween(\Carbon\Carbon::parse(date('Y').'-12-20'), \Carbon\Carbon::parse((date('Y')+1).'-01-20')))
<script src="https://cdnjs.cloudflare.com/ajax/libs/Snowstorm/20131208/snowstorm-min.js" integrity="sha512-rMkLONrw50boYG/6Ku0E8VstfWMRn5D0dX3QZS26Mg0rspYq4EHxYOULuPbv9Be2HBbrrmN8dpPgYUeJ4bINCA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    snowStorm.snowColor = '#99ccff';   // blue-ish snow!?
    snowStorm.flakesMaxActive = 96;    // show more snow on screen at once
</script>
@endif
<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--script for this page-->
@yield('scripts')
<script>

    $(document).ready(function() {

        let seen = getCookie('message_seen');
        if (seen !== 'yes') {
            var unique_id = $.gritter.add({
                title: 'Claim player profiles!',
                text: 'You can now claim player profiles! Check out the new "Claim profiles" menu item in the sidebar.',
                image: 'https://static.prstats.tk/avatars/75/c5/75c53d450505ec0e9f0e2e251c5b2c54.png',
                sticky: true,
                class_name: 'my-sticky-class'
            });
            setCookie('message_seen', 'yes', 7);
        }

        $( "#search" ).autocomplete({
            minLength: 3,
            source: function( request, response ) {
                // Fetch data
                $.ajax({
                    url: "/json/search",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                        "_token": {!! json_encode(csrf_token()) !!},
                    },
                    success: function( data ) {
                        response( data );
                    },
                    error: function( data ) {
                        window.location.reload();
                    }
                });
            },
            select: function (event, ui) {
                window.location = ui.item.value;
                return false;
            },
            focus: function(event, ui){
                $('#search').val(ui.item.label); // display the selected text
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function( ul, item ) {
            return $( "<li style='cursor: pointer'></li>" )
                .append( "<a><i class='fa fa-"+item.icon+"' /> "+ item.label + "</a>" )
                .appendTo( ul );
        };
    });

    var retries = {};
    function reloadImage(img) {
        let pid = $(img).data('pid');
        let src = 'https://robohash.org/%pid.png?set=set5&size=140x140'.replace('%pid', pid);
        retries[src] = retries[src] || 0;

        if (retries[src] < 250) {
            img.src = ''+src;
            retries[src]++;
        }
    }
    function showInfo() {
        $('#blokked').removeClass('hidden');
    }
</script>

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async="" onerror="showInfo()"></script>
<script>
    window.OneSignal = window.OneSignal || [];
    OneSignal.push(function() {
        OneSignal.init({
            appId: "{!! config('services.onesignal.app_id') !!}",
        });
    });
</script>
</body>

</html>