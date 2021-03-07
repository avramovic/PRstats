<div class="row mt content-panel">
    <div class="col-lg-12">
        @if($match->server->wasSeenRecently())
            <h3><i class="fa fa-angle-right"></i> Playing <u>{{ $match->map }}</u> since {{ $match->created_at->format('Y-m-d') }} at {{ $match->created_at->format('H:i') }} ({{ $match->lengthForHumans() }}) </h3>
        @else
            <h3><i class="fa fa-angle-right"></i> Played <u></u> on {{ $match->created_at->format('Y-m-d') }} from {{ $match->created_at->format('H:i') }} to {{ $match->updated_at->format('H:i') }} ({{ $match->lengthForHumans() }})</h3>
        @endif
    </div>
    @include('partials.servers.team_table', ['team' => $match->team1Players()])
    @include('partials.servers.team_table', ['team' => $match->team2Players()])
</div>

