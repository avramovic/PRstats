<div class="row mt content-panel">
    <div class="col-lg-12">
        {{ $slot }}
    </div>
    @include('partials.servers.team_table', ['team' => $match->team1Players()])
    @include('partials.servers.team_table', ['team' => $match->team2Players()])
</div>

