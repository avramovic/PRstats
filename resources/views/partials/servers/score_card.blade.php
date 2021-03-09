<div class="col-md-4 col-sm-4 mb">
    <div class="darkblue-panel pn">
        <div class="darkblue-header">
            <h5>SCORE</h5>
        </div>
        <canvas id="serverscore" height="120" width="120"></canvas>
        <script>
            var doughnutData = [{
                value: {{ $match->team1Players()->sum('pivot.score') }},
                color: "#f68275"
            },
                {
                    value: {{ $match->team2Players()->sum('pivot.score') }},
                    color: "#1c9ca7"
                }
            ];
            var myDoughnut = new Chart(document.getElementById("serverscore").getContext("2d")).Doughnut(doughnutData);
        </script>
        <p>
            <?php $verb = $match->wasSeenRecently() ? 'is' : 'was'; ?>
        @if($match->team1Players()->sum('pivot.score') > $match->team2Players()->sum('pivot.score'))
            Team {{ $match->team1_name }} {{ $verb }} better
        @elseif($match->team1Players()->sum('pivot.score') < $match->team2Players()->sum('pivot.score'))
            Team {{ $match->team2_name }} {{ $verb }} better
        @else
            Game {{ $verb }} indecisive
        @endif
        </p>
        <footer>
            <div class="pull-left">
                <h5>{{ $match->team1_name }}: {!! $match->formatValueHtml($match->team1Players()->sum('pivot.score')) !!}</h5>
            </div>
            <div class="pull-right">
                <h5>{{ $match->team2_name }}: {!! $match->formatValueHtml($match->team2Players()->sum('pivot.score')) !!}</h5>
            </div>
        </footer>
    </div>
    <!--  /darkblue panel -->
</div>