<div class="col-md-4 col-sm-4 mb">
    <div class="darkblue-panel pn">
        <div class="darkblue-header">
            <h5>OPERATING SYSTEM</h5>
        </div>
        <h1 class="mt"><i class="fa {{ stripos($server->os, 'win') !== false ? 'fa-windows' : 'fa-linux'  }} fa-3x"></i></h1>
        <p>{{ $server->os }}</p>
        {{--                    <footer>--}}
        {{--                        <div class="centered">--}}
        {{--                            <h5>{{ $server->os }}</h5>--}}
        {{--                        </div>--}}
        {{--                    </footer>--}}
    </div>
    <!--  /darkblue panel -->
</div>