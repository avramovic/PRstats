<div class="col-lg-3 ds mb">
    <h4>{{ $slot }}</h4>
    @foreach($players as $player)
    <div class="desc">
        <div class="thumb">
            <img data-pid="{{ $player->pid }}" onerror="reloadImage(this)" class="img-circle" src="{{ $player->getAvatarUrl() }}" width="35" height="35" align="">
        </div>
        <div class="details">
            <p>
                @if($player->clan_id)
                    <a href="{{ $player->clan->getLink() }}">{{ $player->clan->name }}</a>
                @endif
                <a href="{{ $player->getLink() }}">{{ $player->name }}</a><br/>
                <em>
                    @if($metric==='created_at')
                        {{ $player->created_at->diffForHumans() }}
                    @elseif($metric==='minutes_played')
                        <abbr title="{{ round($player->minutes_played / 60, 1) }} hour(s)">~{{ Carbon\Carbon::now()->addMinutes($player->minutes_played)->diffForHumans(null, true) }}</abbr>
                    @else
                        {!! $player->formatScoreHtml($metric) !!} {{ str_replace(['total_', 'monthly_'], '', $metric) }}
                    @endif
                </em>
            </p>
        </div>
    </div>
    @endforeach
</div>