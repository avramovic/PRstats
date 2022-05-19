<div class="col-lg-4 col-md-4 col-sm-4 mb">
    <div class="content-panel pn">
        <div id="blog-bg" style="background: url({{ $map->getOriginalMapImageUrl('banner') }}), url({{ $map->getOriginalMapImageUrl('banner', true) }}), url(/img/logo.png); background-size: cover">
            @if($map->lastMatch->wasSeenRecently())
            <div class="badge badge-popular">PLAYING</div>
            @endif
            <div class="blog-title"><a href="{{ $map->getLink() }}">{{ $map->name }}</a></div>
{{--            <div class="server-flag">{!! $server->getCountryFlagHtml() !!}</div>--}}
        </div>
        <div class="blog-text">
                <p><i class="fa fa-angle-right"></i> First seen on {{ $map->created_at->format('Y-m-d') }} at {{ $map->created_at->format('H:i') }}</p>
{{--            @if($map->lastMatch->wasSeenRecently())--}}
{{--                <p><i class="fa fa-angle-right"></i> Last seen {{ $server->updated_at->diffForHumans() }}</p>--}}
{{--                @else--}}
                <p><i class="fa fa-angle-right"></i> Last seen {{ $map->lastMatch->updated_at->diffForHumans() }} on <a href="{{ $map->lastMatch->server->getLink() }}">{{ $map->lastMatch->server->name }}</a></p>
                <p><i class="fa fa-angle-right"></i> Played {{ $map->matches_count }} times so far.</p>
{{--            @endif--}}
        </div>
    </div>
</div>