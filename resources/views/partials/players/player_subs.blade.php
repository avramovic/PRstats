<div class="col-lg-3 ds mb">
    <h4>{{ $slot }}</h4>
    @foreach($players as $player)
        @php
        if (!$player instanceof \PRStats\Models\Player) {
            $subTime = $player->{$metric};
            $player = $player->player;
            $player->{$metric} = $subTime;
        }
        @endphp
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
                    @if(\Illuminate\Support\Str::endsWith($metric, '_at'))
                        {{ $player->{$metric}->diffForHumans() }}
                    @else
                        {!! $player->formatValueHtml($player->$metric) !!} {{ $player->$metric == 1 ? \Illuminate\Support\Str::singular(str_replace(['_count'], '', $metric)) : str_replace(['_count'], '', $metric) }}
                    @endif
                </em>
            </p>
        </div>
    </div>
    @endforeach
</div>