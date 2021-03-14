<div class="col-md-4 col-sm-4 mb">
    <div class="weather pn"
         style="background: #666 url({!! $lastMatch->getMapImageUrl('background') !!}); background-size: cover;">
        <i title="OFFLINE" class="fa fa-eye-slash fa-4x"></i>
        <h4>LAST SEEN PLAYING</h4>
        <h2>{{ $lastMatch->map->name }}</h2>
        <h4><abbr title="{{ $player->updated_at->format('Y-m-d') }}">{{ $player->updated_at->diffForHumans() }}</abbr></h4>
            <h4>on <a href="{{ $lastMatch->server->getLink() }}">{{ $lastMatch->server->name }}</a></h4>
    </div>
</div>