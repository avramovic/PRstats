<div class="col-md-4 col-sm-4 mb">
    <div class="darkblue-panel pn">
        <div class="darkblue-header">
            <h5>SERVER CAPACITY</h5>
        </div>
        <canvas id="serverstatus02" height="120" width="120"></canvas>
        <script>
            var doughnutData = [{
                value: {{ $server->num_players }},
                color: "#f68275"
            },
                {
                    value: {{ $server->max_players - $server->num_players }},
                    color: "#1c9ca7"
                }
            ];
            var myDoughnut = new Chart(document.getElementById("serverstatus02").getContext("2d")).Doughnut(doughnutData);
        </script>
        <p>{{ $server->max_players - $server->num_players }} free slots ({{ $server->reserved_slots }} reserved)</p>
        <footer>
            <div class="pull-left">
                <h5><i class="fa fa-users"></i> {{ $server->num_players }} / {{ $server->max_players }}</h5>
            </div>
            <div class="pull-right">
                <h5>{{ round(($server->num_players/$server->max_players)*100, 2) }}% full</h5>
            </div>
        </footer>
    </div>
    <!--  /darkblue panel -->
</div>