@extends('layouts.app')

@section('title')
    Top players in {{ date('F') }}
@endsection

@section('content')
    <table align="center">
        <thead>
            <tr>
                <th>#</th>
                <th>Clan</th>
                <th>Name</th>
                <th>Monthly score</th>
                <th>Monthly kills</th>
                <th>Monthly deaths</th>
            </tr>
        </thead>
        <tbody>
    <?php $nr = 1; ?>
    @foreach($players as $player)
        <tr>
            <td>{{ $nr++ }}</td>
            <td>
                @if($player->clan)
                    <span class="clan"><a href="{{ $player->clan->getLink() }}">{{ $player->clan_name }}</a></span>
                @else
                    &mdash;
                @endif
            </td>
            <td><a href="{{ $player->getLink() }}">{{ $player->name }}</a></td>
            <td>{!! $player->formatScoreHtml('monthly_score') !!}</td>
            <td>{!! $player->formatScoreHtml('monthly_kills') !!}</td>
            <td>{!! $player->formatScoreHtml('monthly_deaths') !!}</td>
        </tr>
    @endforeach

        </tbody>
    </table>

    <!-- testing deployment -->
@endsection

@section('right')
    <h2>Welcome!</h2>
    <p>PR stats is a website which tracks your <a href="http://www.realitymod.com" target="_blank">Project Reality</a> stats in real-time. The table on the left shows top 50 players
        which have been playing in the last month and their monthly score.</p>
    <p>Due to PR being a frankenstein of a game, your stats won't count until you are in the top 64 players on the server.
        Consider that a feature, a nice way of filtering AFKers and lousy players :-)</p>
@endsection

@section('rightbottom')
    <div class="right">
        <div class="darkbg">
            <a href="{{ $newest->getLink() }}">
                <img src="https://vanillicon.com/{{ md5($newest->name) }}.png" class="avatar" style="width: 70px; height: 70px;">
            </a>
            <h3>Newest player: {{ $newest->created_at->diffForHumans() }}</h3>
            <p>
                @if($newest->clan)
                    <a href="{{ $newest->clan->getLink() }}">{{ $newest->clan->name }}
                        @endif

                        <a href="{{ $newest->getLink() }}">{{ $newest->name }}</a>
            </p>
        </div>
    </div>


    <div class="right">
        <div class="darkbg">
            <a href="{{ $longest->getLink() }}">
                <img src="https://vanillicon.com/{{ md5($longest->name) }}.png" class="avatar" style="width: 70px; height: 70px;">
            </a>
            <h3>Longest in-game: <abbr title="{{ round($longest->minutes_played / 60, 1) }} hour(s)">{{ Carbon\Carbon::now()->addMinutes($longest->minutes_played)->diffForHumans(null, true) }}</abbr></h3>
            <p>
            @if($longest->clan)
                <a href="{{ $longest->clan->getLink() }}">{{ $longest->clan->name }}
            @endif

                <a href="{{ $longest->getLink() }}">{{ $longest->name }}</a>
            </p>
        </div>
    </div>

    <div class="right">
        <div class="darkbg">
            <a href="{{ $mostKills->getLink() }}">
                <img src="https://vanillicon.com/{{ md5($mostKills->name) }}.png" class="avatar" style="width: 70px; height: 70px;">
            </a>
            <h3>Most kills: {{ $mostKills->total_kills }}</h3>
            <p>
            @if($mostKills->clan)
                <a href="{{ $mostKills->clan->getLink() }}">{{ $mostKills->clan->name }}
            @endif

                <a href="{{ $mostKills->getLink() }}">{{ $mostKills->name }}</a>
            </p>
        </div>
    </div>

    <div class="right">
        <div class="darkbg">
            <a href="{{ $mostDeaths->getLink() }}">
                <img src="https://vanillicon.com/{{ md5($mostDeaths->name) }}.png" class="avatar" style="width: 70px; height: 70px;">
            </a>
            <h3>Most deaths: {{ $mostDeaths->total_deaths }}</h3>
            <p>
            @if($mostDeaths->clan)
                <a href="{{ $mostDeaths->clan->getLink() }}">{{ $mostDeaths->clan->name }}
            @endif

                <a href="{{ $mostDeaths->getLink() }}">{{ $mostDeaths->name }}</a>
            </p>
        </div>
    </div>
@endsection