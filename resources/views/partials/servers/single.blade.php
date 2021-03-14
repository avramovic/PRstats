<div class="col-lg-4 col-md-4 col-sm-4 mb">
    <div class="content-panel pn">
        <div id="blog-bg" style="background: url({{ $server->getLastMapImageUrl('banner') }}); background-size: cover">
            @if($server->wasSeenRecently())
            <div class="badge badge-popular">@if($server->num_players >= $server->max_players-$server->reserved_slots) FULL @else ONLINE @endif</div>
            @endif
            <div class="blog-title"><a href="{{ $server->getLink() }}">{{ $server->name }}</a></div>
            <div class="server-flag">{!! $server->getCountryFlagHtml() !!}</div>
        </div>
        <div class="blog-text">
            @if($server->wasSeenRecently())
                <p><i class="fa fa-angle-right"></i> Playing <u>{{ $server->lastMatch()->map->name }}</u> since {{ $server->lastMatch()->created_at->format('Y-m-d') }} at {{ $server->lastMatch()->created_at->format('H:i') }} ({{ $server->lastMatch()->lengthForHumans() }}) </p>
                <p><i class="fa fa-angle-right"></i> Online players: {{ $server->num_players }} / {{ $server->max_players-$server->reserved_slots }} </p>
            @else
                <p><i class="fa fa-angle-right"></i> Played <u>{{ $server->lastMatch()->map->name }}</u> on {{ $server->lastMatch()->created_at->format('Y-m-d') }} from {{ $server->lastMatch()->created_at->format('H:i') }} to {{ $server->lastMatch()->updated_at->format('H:i') }}</p>
                <p><i class="fa fa-angle-right"></i> Last seen {{ $server->updated_at->diffForHumans() }}</p>
            @endif
        </div>
    </div>
</div>